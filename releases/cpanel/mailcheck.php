<?php

/**
 * Tricetech mail delivery tester.
 *
 * Why: the site has no SSH access, so this page boots the real Laravel app and
 * sends a REAL test notification using the exact same mailable the form uses.
 * Whatever the mail server says, this page prints it.
 *
 * How to use:
 *   1. Put this file in public_html/  (it is deleted from the release otherwise)
 *   2. Open   https://yourdomain/mailcheck.php?key=tt-mail-9f3c2b71a4
 *      -> shows the current settings and a preview, sends NOTHING.
 *   3. Add &send=1 to actually send the test message.
 *      Add &mailer=sendmail  or &mailer=smtp  to test one transport without
 *      editing .env.
 *      Add &to=you@yourdomain.com  to send to a single address instead of all.
 *      Add &force=1 to repeat a test within 60 seconds of a successful one.
 *
 * Every attempt is recorded in tricetech_app/storage/mailcheck-last-attempt.json
 * and reported at the top of section 3, so reloading the page never hides the
 * result of the send you just made.
 *   4. DELETE this file when finished.
 *
 * Nothing here prints a password. Do not leave it online.
 */

use App\Mail\ArchitectureInquiry;
use Illuminate\Support\Facades\Mail;

$token = 'tt-mail-9f3c2b71a4';

header('Content-Type: text/plain; charset=utf-8');

if (! isset($_GET['key']) || ! hash_equals($token, (string) $_GET['key'])) {
    header('HTTP/1.1 403 Forbidden');
    echo "Forbidden. Append ?key=... with the token from your deployment notes.\n";
    exit;
}

function row($label, $value)
{
    printf("%-30s %s\n", $label, $value);
}

function mask($value)
{
    if ($value === null || $value === '') {
        return '(empty)';
    }
    if (strlen($value) <= 4) {
        return str_repeat('*', strlen($value));
    }

    return substr($value, 0, 2).str_repeat('*', max(4, strlen($value) - 4)).substr($value, -2);
}

function tail($file, $lines)
{
    if (! is_readable($file)) {
        return null;
    }
    $all = @file($file, FILE_IGNORE_NEW_LINES);

    return $all === false ? null : array_slice($all, -1 * $lines);
}

$webRoot = __DIR__;
$appPath = dirname($webRoot).'/tricetech_app';
$logFile = $appPath.'/storage/logs/laravel.log';

echo "TRICETECH MAIL TESTER\n";
echo "=====================\n\n";

if (! file_exists($appPath.'/vendor/autoload.php')) {
    echo "Cannot continue: tricetech_app is not beside public_html.\n";
    exit;
}

/* boot the real application */
require $appPath.'/vendor/autoload.php';
$app = require $appPath.'/bootstrap/app.php';
$app->usePublicPath($webRoot);
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->bootstrap();

/* optional transport override for testing without touching .env */
$override = isset($_GET['mailer']) ? (string) $_GET['mailer'] : null;
if ($override !== null) {
    if (! in_array($override, ['smtp', 'sendmail', 'log', 'array'], true)) {
        echo "Unknown mailer '$override'. Use smtp, sendmail, log or array.\n";
        exit;
    }
    config(['mail.default' => $override]);
}

$mailer = config('mail.default');
$smtp = config('mail.mailers.smtp');
$recipients = config('tricetech.inquiry_recipients');
$single = isset($_GET['to']) && $_GET['to'] !== '' ? [(string) $_GET['to']] : null;
$targets = $single ?: $recipients;
$send = isset($_GET['send']);

echo '1. CURRENT SETTINGS'.($override ? "  (transport overridden to '$override')" : '')."\n";
row('active mail transport', $mailer);
row('from address', config('mail.from.address').'  <'.config('mail.from.name').'>');
row('recipients', is_array($recipients) ? implode(', ', $recipients) : '(none configured!)');
if ($single) {
    row('sending this test to', implode(', ', $targets).'  (overridden)');
}

if ($mailer === 'smtp') {
    echo "\n   smtp details:\n";
    row('   host', $smtp['host'] ?: '(empty)');
    row('   port', $smtp['port'] ?: '(empty)');
    row('   scheme', $smtp['scheme'] ? ($smtp['scheme'].'  (from MAIL_SCHEME)') : '(auto: 465=>smtps, else smtp)');
    row('   username', $smtp['username'] ?: '(empty)');
    row('   password', mask($smtp['password']));
    row('   local domain (EHLO)', $smtp['local_domain'] ?: '(empty)');
}

if ($mailer === 'sendmail') {
    echo "\n";
    row('sendmail path', config('mail.mailers.sendmail.path'));
    row('binary exists?', file_exists('/usr/sbin/sendmail') ? 'yes' : '*** NO - wrong path for this host ***');
    row('binary executable?', is_executable('/usr/sbin/sendmail') ? 'yes' : '*** NO ***');
}

if ($mailer === 'log') {
    echo "\n   *** MAIL_MAILER is still 'log'. Nothing is emailed at all. ***\n";
    echo "   *** This is why the form produced no notification. ***\n";
    echo "   *** You can test sending without editing .env by adding &mailer=sendmail ***\n";
    echo "   *** or &mailer=smtp to the URL - see step 3 below. ***\n";
}
echo "\n";

/* ---------------------------------------------------------- local mailboxes */

$home = dirname($webRoot);
$fromDomain = substr(strrchr(config('mail.from.address'), '@'), 1);
$mailRoot = $home.'/mail/'.$fromDomain;

if ($mailer === 'sendmail') {
    echo "1b. LOCAL MAILBOXES ON THIS SERVER (cPanel > Email Accounts)\n";
    if (! is_dir($mailRoot)) {
        echo '   No mail directory at '.$mailRoot."\n";
        echo '   That means this server does not handle mail for '.$fromDomain.".\n";
        echo "   sendmail would then deliver to a remote mailbox, where the domain's\n";
        echo "   SPF record decides whether it is accepted. Using SMTP through the real\n";
        echo "   provider instead is safer in that case.\n\n";
    } else {
        $check = array_unique(array_merge($targets, [config('mail.from.address')]));
        foreach ($check as $address) {
            $parts = explode('@', $address);
            $local = isset($parts[0]) ? $parts[0] : '';
            $domain = isset($parts[1]) ? $parts[1] : $fromDomain;
            $path = $home.'/mail/'.$domain.'/'.$local;
            $isFrom = ($address === config('mail.from.address'));
            row('   '.$address, (is_dir($path) || file_exists($path) ? 'exists' : '*** NOT FOUND ***')
                .($isFrom ? '  (used as the From address)' : ''));
            if (! is_dir($path) && ! file_exists($path)) {
                if ($isFrom) {
                    echo "      -> create it in cPanel > Email Accounts, or point\n";
                    echo "         MAIL_FROM_ADDRESS at a mailbox that does exist.\n";
                } else {
                    echo "      -> notifications to it would bounce. Create it in cPanel >\n";
                    echo "         Email Accounts, or remove it from INQUIRY_RECIPIENT.\n";
                }
            }
        }
        echo "\n   Local delivery keeps everything on this host, so no SPF/DKIM check\n";
        echo "   is involved. Replies still work: reply-to is set to the visitor.\n\n";
    }
}

/* ------------------------------------------------------------ reachability */

if ($mailer === 'smtp' && $smtp['host']) {
    echo "2. CAN THIS SERVER REACH THE MAIL SERVER?\n";
    foreach ([(int) $smtp['port']] as $port) {
        $errno = 0;
        $errstr = '';
        $start = microtime(true);
        $sock = @fsockopen($smtp['host'], $port, $errno, $errstr, 10);
        $ms = round((microtime(true) - $start) * 1000);
        if ($sock) {
            $banner = @fgets($sock, 512);
            fclose($sock);
            row('connect '.$smtp['host'].':'.$port, "OK in {$ms}ms");
            row('server said', trim((string) $banner) ?: '(no banner read)');
        } else {
            row('connect '.$smtp['host'].':'.$port, "*** FAILED in {$ms}ms: {$errstr} ({$errno}) ***");
            echo "   A timeout or 'Connection refused' here means the host firewall blocks\n";
            echo "   outbound mail, or the host/port is wrong. Not fixable from .env alone.\n";
        }
    }
    echo "\n";
} else {
    echo "2. CAN THIS SERVER REACH THE MAIL SERVER?\n   Not applicable for transport '$mailer'.\n\n";
}

/* --------------------------------------------------------------- the send */

echo "3. SEND THE REAL TEST NOTIFICATION\n";

$sample = [
    'email' => 'test.visitor@example.com',
    'interest' => 'Mail delivery test (not a real inquiry)',
    'message' => 'This is an automated delivery test sent at '.date('Y-m-d H:i:s T').'. If you are reading this in your inbox, the inquiry form will reach you too. Reply-to points at test.visitor@example.com, exactly as a real submission would.',
];

$mailable = new ArchitectureInquiry($sample);

$stateFile = $appPath.'/storage/mailcheck-last-attempt.json';
$lastAttempt = is_readable($stateFile) ? json_decode((string) file_get_contents($stateFile), true) : null;

if (is_array($lastAttempt) && isset($lastAttempt['time'])) {
    echo '   Last attempt: '.date('Y-m-d H:i:s T', (int) $lastAttempt['time']).' -> '.strtoupper((string) $lastAttempt['status'])."\n";
    echo '     '.($lastAttempt['detail'] ?? '(no detail)')."\n";
    echo '     sent to: '.($lastAttempt['to'] ?? '(unknown)')."\n\n";
} else {
    echo "   No test has been sent from this page yet.\n\n";
}

echo "   Preview of what gets sent:\n";
try {
    $rendered = $mailable->render();
    echo '   Subject : '.'Website inquiry: '.$sample['interest']."\n";
    echo '   To      : '.implode(', ', $targets)."\n";
    echo '   Reply-To: '.$sample['email']."\n";
    echo "   Body    :\n";
    foreach (explode("\n", trim($rendered)) as $l) {
        echo '     '.$l."\n";
    }
} catch (Throwable $e) {
    echo '   *** The notification template itself fails to render: '.$e->getMessage()."\n";
    echo "   *** That alone would break every form submission.\n";
}
echo "\n";

if (! $send) {
    echo "   Nothing sent yet. Re-open this URL with &send=1 added to send it.\n\n";
} else {
    $secondsSince = is_array($lastAttempt) && isset($lastAttempt['time']) ? time() - (int) $lastAttempt['time'] : null;
    $previousSucceeded = is_array($lastAttempt) && ($lastAttempt['status'] ?? '') === 'success';
    $throttled = $previousSucceeded && $secondsSince !== null && $secondsSince < 60 && ! isset($_GET['force']);

    if ($throttled) {
        echo "   The previous test SUCCEEDED; this one was skipped to avoid flooding the\n";
        echo '   inboxes. Reload in '.(60 - (int) $secondsSince)." seconds, or add &force=1 to send now.\n";
        echo "   A failed attempt is never throttled.\n\n";
    } else {
        echo '   Sending to '.implode(', ', $targets)." ...\n\n";
        try {
            Mail::to($targets)->send($mailable);
            file_put_contents($stateFile, json_encode([
                'time' => time(),
                'status' => 'success',
                'detail' => 'The mail server accepted the message.',
                'to' => implode(', ', $targets),
            ]));
            echo "   *** SUCCESS - the mail server ACCEPTED the message. ***\n\n";
            echo "   Accepted is not the same as delivered. Now check the inboxes AND the\n";
            echo "   spam folders for a subject starting 'Website inquiry: Mail delivery test'.\n";
            echo "   If it is missing everywhere, the receiving side rejected it - usually SPF\n";
            echo "   or DKIM. cPanel -> Email Deliverability names the missing DNS records.\n";
            echo "   cPanel -> Track Delivery shows what happened to each message.\n";
        } catch (Throwable $e) {
            echo "   *** FAILED - the mail server rejected or could not be reached. ***\n\n";
            row('exception', get_class($e));
            row('message', $e->getMessage());
            $prev = $e->getPrevious();
            $depth = 0;
            while ($prev && $depth < 4) {
                echo "\n   caused by (".get_class($prev)."):\n     ".$prev->getMessage()."\n";
                $prev = $prev->getPrevious();
                $depth++;
            }
            echo "\n   Common meanings:\n";
            echo "     535 / 'Authentication unsuccessful'  -> wrong username or password\n";
            echo "     550 5.7.60 / 'Sender address rejected' -> MAIL_FROM_ADDRESS is not the\n";
            echo "        mailbox you authenticated as (Office 365 rejects this)\n";
            echo "     550 / 'relay not permitted'          -> the host refuses unauthenticated mail\n";
            echo "     'Connection could not be established' -> blocked port or wrong host\n";
            file_put_contents($stateFile, json_encode([
                'time' => time(),
                'status' => 'failed',
                'detail' => get_class($e).': '.substr($e->getMessage(), 0, 300),
                'to' => implode(', ', $targets),
            ]));
        }
        echo "\n";
    }
}

/* ------------------------------------------------------------------- log */

echo "4. LARAVEL LOG AFTER THE ATTEMPT\n";
$lines = tail($logFile, 30);
if ($lines === null) {
    echo '   No readable log at '.$logFile."\n";
} else {
    foreach ($lines as $l) {
        echo '   '.$l."\n";
    }
    if (filesize($logFile) === 0) {
        echo "   (log file is empty)\n";
    }
}
echo "\n";

echo "5. WHO IS SENDING (for SPF/DKIM comparison)\n";
row('server hostname', gethostname() ?: '(unknown)');
row('server IP', isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '(unknown)');
row('from domain', substr(strrchr(config('mail.from.address'), '@'), 1));
echo "   cPanel -> Email Deliverability lists the SPF/DKIM records this domain needs.\n\n";

echo "REMINDER: delete mailcheck.php from public_html when you are done.\n";
