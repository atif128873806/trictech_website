<?php

use Illuminate\Http\Request;

/**
 * Tricetech deployment doctor - v2.
 *
 * v1 proved the layout, .env, permissions and Laravel bootstrap are healthy.
 * v2 goes further: it dispatches a REAL request through the actual HTTP kernel
 * (the same pipeline index.php uses), so whatever crashes the front page
 * crashes here too - where we can read the reason.
 *
 * Usage:
 *   1. Put this file in public_html/  (https://yourdomain/doctor.php)
 *   2. Read the output top to bottom.
 *   3. DELETE IT when finished.
 *
 * Options:
 *   ?path=/infrastructure   test a different URL path
 *   ?log=1                 always print the Laravel log tail
 */
header('Content-Type: text/plain; charset=utf-8');

function row($label, $value)
{
    printf("%-30s %s\n", $label, $value);
}

function tail($file, $lines)
{
    if (! is_readable($file)) {
        return null;
    }
    $all = @file($file, FILE_IGNORE_NEW_LINES);
    if ($all === false) {
        return null;
    }

    return array_slice($all, -1 * $lines);
}

echo "TRICETECH DEPLOYMENT DOCTOR v2\n";
echo "==============================\n\n";

$webRoot = __DIR__;
$appPath = dirname($webRoot).'/tricetech_app';
$logFile = $appPath.'/storage/logs/laravel.log';

/* ------------------------------------------------------------------ server */

echo "1. SERVER\n";
row('PHP version', PHP_VERSION.'  '.(PHP_VERSION_ID >= 80300 ? 'OK' : '*** TOO OLD ***'));
row('PHP interface', PHP_SAPI);
row('memory_limit', ini_get('memory_limit'));
row('open_basedir', ($open = ini_get('open_basedir')) ? $open : '(not set) OK');
echo "\n   Extension check (composer.json requires). Missing ones do not cause a\n";
echo "   500 by themselves, but Laravel wants them:\n";
$exts = ['ctype', 'curl', 'dom', 'fileinfo', 'filter', 'hash', 'mbstring', 'openssl', 'pdo', 'session', 'tokenizer', 'xml'];
foreach ($exts as $ext) {
    row('   ext-'.$ext, extension_loaded($ext) ? 'loaded' : '*** MISSING ***');
}
echo "\n";

/* -------------------------------------------------------- front controller */

echo "2. FRONT CONTROLLER (public_html/index.php)\n";
$front = $webRoot.'/index.php';
if (! file_exists($front)) {
    row('index.php', '*** MISSING *** the site cannot work without it');
} else {
    $src = file_get_contents($front);
    row('size / modified', filesize($front).' bytes, '.date('Y-m-d H:i', filemtime($front)));
    row('points at tricetech_app', strpos($src, 'tricetech_app') !== false ? 'yes OK' : 'NO *** host stock file? ***');
    row('sets usePublicPath()', strpos($src, 'usePublicPath') !== false ? 'yes OK' : 'NO *** @vite will fail ***');
    row('has maintenance check', strpos($src, 'maintenance.php') !== false ? 'yes' : 'no');
    row('first line', trim(strtok($src, "\n")));
}

echo "\n   .htaccess (this is what sends requests to index.php):\n";
$ht = $webRoot.'/.htaccess';
if (! file_exists($ht)) {
    echo "   *** MISSING *** without it, only the exact URL / works at all.\n";
} else {
    $hsrc = file_get_contents($ht);
    row('   size', filesize($ht).' bytes');
    row('   rewrites to index.php', strpos($hsrc, 'RewriteRule ^ index.php') !== false ? 'yes OK' : '*** NO - host replaced it? ***');
    row('   RewriteEngine On', strpos($hsrc, 'RewriteEngine On') !== false ? 'yes OK' : '*** NO ***');
    echo "\n   --- .htaccess start ---\n";
    echo '   '.str_replace("\n", "\n   ", trim($hsrc))."\n   --- .htaccess end ---\n";
}

echo "\n   bootstrap/cache contents (stale caches here cause 500s):\n";
$bc = $appPath.'/bootstrap/cache';
$found = [];
if (is_dir($bc)) {
    foreach (scandir($bc) as $f) {
        if ($f !== '.' && $f !== '..' && $f !== '.gitignore') {
            $found[] = $f.' ('.filesize($bc.'/'.$f).'b)';
        }
    }
}
if (count($found)) {
    echo '   *** '.implode(', ', $found)."\n";
    echo "   *** Cached config/routes can pin values from another machine. If the log\n";
    echo "   *** mentions an old path or a stale value, delete these files and reload.\n";
} else {
    echo "   empty OK\n";
}
echo "\n";

/* ----------------------------------------------------- full request test */

echo "3. LIVE REQUEST TEST (this runs the real front-page pipeline)\n";
if (! file_exists($appPath.'/vendor/autoload.php')) {
    echo "   Skipped: the application folder is not beside public_html.\n";
} else {
    $path = isset($_GET['path']) ? $_GET['path'] : '/';
    if (substr($path, 0, 1) !== '/') {
        $path = '/'.$path;
    }
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $url = 'https://'.$host.$path;
    row('requesting', $url);

    $before = file_exists($logFile) ? filesize($logFile) : 0;

    try {
        require $appPath.'/vendor/autoload.php';
        $app = require $appPath.'/bootstrap/app.php';
        $app->usePublicPath($webRoot);

        $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
        $request = Request::create($url, 'GET');
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $body = (string) $response->getContent();

        row('HTTP status', $status);
        row('response size', strlen($body).' bytes');
        if ($status >= 200 && $status < 400) {
            echo "\n   *** The application served this page fine. If your browser still\n";
            echo "   *** shows 500 on the same URL, the problem is NOT in the app code -\n";
            echo "   *** look at error_log below (line ~5) and at LiteSpeed/rewrite rules.\n";
        } else {
            echo "\n   *** The app returned a server error for this URL. The reason is in\n";
            echo "   *** the Laravel log tail below.\n";
        }
        echo "\n   First 400 characters the app returned:\n";
        echo '   '.str_replace("\n", "\n   ", substr(trim(strip_tags($body)), 0, 400))."\n";
    } catch (Throwable $e) {
        echo "\n   *** UNCAUGHT FAILURE ***\n";
        row('exception', get_class($e));
        row('message', $e->getMessage());
        row('file', $e->getFile().':'.$e->getLine());
        echo "\n   Trace (application frames only):\n";
        foreach ($e->getTrace() as $frame) {
            if (! isset($frame['file'])) {
                continue;
            }
            if (strpos($frame['file'], $appPath) === false && strpos($frame['file'], $webRoot) === false) {
                continue;
            }
            echo '     '.$frame['file'].':'.(isset($frame['line']) ? $frame['line'] : '?')."\n";
        }
    }
}
echo "\n";

/* ----------------------------------------------------------------- log tail */

echo "4. LARAVEL LOG TAIL\n";
if (! file_exists($logFile)) {
    echo '   No log file at '.$logFile." yet.\n";
} else {
    echo '   '.$logFile.' ('.filesize($logFile)." bytes)\n\n";
    $lines = tail($logFile, 45);
    if ($lines === null) {
        echo "   Exists but is not readable by the web user.\n";
    } else {
        foreach ($lines as $l) {
            echo '   '.$l."\n";
        }
    }
}
echo "\n";

/* ---------------------------------------------------------------- error_log */

echo "5. PHP ERROR LOG (where fatals land when display_errors is off)\n";
$candidates = [
    'ini error_log' => ini_get('error_log'),
    'public_html/error_log' => $webRoot.'/error_log',
    'app error_log' => $appPath.'/error_log',
    'home error_log' => dirname($webRoot).'/error_log',
];
$any = false;
foreach ($candidates as $label => $file) {
    if (! $file || ! file_exists($file)) {
        row($label, 'not found');

        continue;
    }
    $any = true;
    echo "\n   --- ".$label.' ('.$file.', '.filesize($file)." bytes) ---\n";
    $lines = tail($file, 25);
    if ($lines === null) {
        echo "   not readable\n";

        continue;
    }
    foreach ($lines as $l) {
        echo '   '.$l."\n";
    }
}
if (! $any) {
    echo "\n   Nothing to read. In cPanel open 'Errors' (Metrics -> Errors) instead.\n";
}
echo "\n";

/* ------------------------------------------------------------------- guide */

echo "6. NEXT STEPS\n";
echo "   - Status 200 above + browser shows 500 for the same URL => server/rewrite\n";
echo "     layer, not the app. Check public_html/.htaccess was not edited by the host.\n";
echo "   - Status 500 above => the fix is named in section 4.\n";
echo "   - Section 5 empty but browser 500s => ask the host for the LiteSpeed log.\n\n";
echo "REMINDER: delete doctor.php from public_html when you are done.\n";
