<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = $argv[1] ?? null;
if (! $email) {
    echo "Usage: php send_reset.php user@example.com\n";
    exit(2);
}

use Illuminate\Support\Facades\Password;

$status = Password::sendResetLink(['email' => $email]);
echo "Status: " . $status . PHP_EOL;
