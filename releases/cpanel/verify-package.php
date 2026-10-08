<?php

/**
 * Release verifier — run inside a built release, never deployed.
 *
 * The build script copies this file into the release's public_html as
 * _verify.php, runs it, and deletes it again. It boots the packaged application
 * exactly as the web server will: the packaged .env, the packaged vendor
 * directory, the packaged views, and the same public path override as the
 * shipped front controller.
 *
 * Running the packaged tree rather than the source tree is the point. A shipped
 * .env that cannot be parsed, a dependency missing from the production install,
 * a compiled view left over from local development or a Blade error all fail
 * here instead of on the live site.
 *
 * Exit code 0 means the release may be zipped.
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$appPath = __DIR__.'/../tricetech_app';
$failures = [];

$report = function (string $label, string $detail = '') use (&$failures): void {
    echo '  '.str_pad($label, 34).$detail."\n";
};

// Gates report and stop before the next stage runs, so the first readable
// failure is the one an operator sees rather than a later fatal error.
$gate = function () use (&$failures): void {
    if ($failures === []) {
        return;
    }

    echo "\nRELEASE REJECTED\n";
    foreach ($failures as $failure) {
        echo '  - '.$failure."\n";
    }

    exit(1);
};

// ---------------------------------------------------------------- packaged env

$envPath = $appPath.'/.env';

if (! is_file($envPath)) {
    $failures[] = 'The packaged .env is missing.';
} else {
    $env = file_get_contents($envPath);

    foreach (['APP_KEY=base64:', 'APP_DEBUG=false', 'APP_ENV=production'] as $expected) {
        if (! str_contains($env, $expected)) {
            $failures[] = 'The packaged .env is missing '.$expected;
        }
    }

    // phpdotenv cannot parse an unquoted value containing a space, and the whole
    // application then returns a blank 500. The stock Laravel mailer line is the
    // usual offender, so it is checked here rather than discovered on the host.
    foreach (preg_split('/\R/', $env) as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $value = rtrim($value);

        // phpdotenv rejects an unquoted value containing whitespace, whatever it
        // is. A quoted value may contain spaces, and a trailing comment is
        // stripped first so it is not mistaken for part of the value.
        if (! str_starts_with($value, '"') && ! str_starts_with($value, "'")) {
            $value = rtrim((string) preg_replace('/\s+#.*$/', '', $value));

            if (preg_match('/\s/', $value)) {
                $failures[] = 'Unquoted value with a space in .env: '.$name.'='.$value;
            }
        }
    }

    $report('packaged .env', is_file($envPath) ? 'present' : 'missing');
}

$gate();

// ------------------------------------------------------------ leftover scratch

$scratch = array_merge(
    glob($appPath.'/bootstrap/cache/*.php') ?: [],
    glob($appPath.'/storage/framework/views/*.php') ?: [],
    glob($appPath.'/storage/logs/*.log') ?: [],
    is_dir($appPath.'/storage/app/design-preview') ? [$appPath.'/storage/app/design-preview'] : [],
);

if ($scratch !== []) {
    $failures[] = 'Development scratch files would ship: '.implode(', ', $scratch);
}

$report('development scratch', $scratch === [] ? 'clean' : count($scratch).' file(s)');

$gate();

// ------------------------------------------------------------------- boot test

require $appPath.'/vendor/autoload.php';

$app = require $appPath.'/bootstrap/app.php';
$app->usePublicPath(__DIR__);

$kernel = $app->make(Kernel::class);
$response = $kernel->handle(Request::create('/', 'GET'));

$status = $response->getStatusCode();
$body = $response->getContent();
$report('GET /', 'HTTP '.$status.', '.number_format(strlen($body)).' bytes');

if ($status !== 200) {
    $failures[] = 'The packaged home page returned HTTP '.$status.'.';
}

foreach ([
    'brand wordmark' => 'Tricetech',
    'inline drawing' => 'class="schematic"',
    'figure caption' => 'Figure 01',
    'claims strip' => 'Enterprise SLA backed',
    'inquiry form' => 'name="email"',
] as $label => $needle) {
    $found = str_contains($body, $needle);
    $report($label, $found ? 'present' : 'MISSING');

    if (! $found) {
        $failures[] = 'The packaged home page is missing: '.$label.'.';
    }
}

// The stylesheet the page references must exist in the shipped manifest. The
// manifest's paths are relative to the build directory, not to the web root.
$manifest = json_decode((string) @file_get_contents(__DIR__.'/build/manifest.json'), true);
$entry = $manifest['resources/css/app.css']['file'] ?? null;
$hasCss = $entry && is_file(__DIR__.'/build/'.$entry);

$report('compiled stylesheet', $hasCss ? $entry : 'missing from build/');
if (! $hasCss) {
    $failures[] = 'The compiled stylesheet referenced by the manifest is not in public_html/build.';
}

// ------------------------------------------------------------------- verdict

echo "\n";

if ($failures !== []) {
    echo "RELEASE REJECTED\n";
    foreach ($failures as $failure) {
        echo '  - '.$failure."\n";
    }

    exit(1);
}

echo "RELEASE VERIFIED — the packaged application serves the home page.\n";

exit(0);
