<?php

// Log all incoming requests with full details for debugging
$log_file = __DIR__ . '/webhook.log';
file_put_contents($log_file, date('Y-m-d H:i:s') . " - Webhook triggered\n", FILE_APPEND);

// Log the entire request (header + body)
// file_put_contents($log_file, "Request Headers: " . json_encode(getallheaders()) . "\n", FILE_APPEND);

// Security: verify the webhook came from GitHub
$secret = 'myflatinfo-super';  // Same secret as in GitHub webhook
// 1. Read the raw payload first
$payload = file_get_contents('php://input');
// file_put_contents($log_file, "Payload: " . $payload . "\n", FILE_APPEND);

// 2. Get the signature from GitHub headers
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// 3. Compute the expected signature
$expected_signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

// 4. Log received and expected signatures
// file_put_contents($log_file, "Received Signature: " . $signature . "\n", FILE_APPEND);
// file_put_contents($log_file, "Expected Signature: " . $expected_signature . "\n", FILE_APPEND);

// 5. Verify the signature
if (!hash_equals($expected_signature, $signature)) {
    // More secure comparison
    file_put_contents($log_file, "Invalid signature\n", FILE_APPEND);
    die('Invalid signature');
}

// 6. Change to the project directory
chdir('/var/www/myflatinfo-super');

putenv('HOME=/var/www');

// 7. Discard any local changes and reset to the latest commit
exec('git reset --hard HEAD 2>&1', $reset_output);
// file_put_contents($log_file, "Git Reset Output: " . implode("\n", $reset_output) . "\n", FILE_APPEND);

// 7. Pull the latest code
exec('git pull origin main 2>&1', $output);
file_put_contents($log_file, "Git Pull Output: " . implode("\n", $output) . "\n", FILE_APPEND);
// 8. Install/update PHP packages
// exec('composer install 2>&1', $composer_output);
// file_put_contents($log_file, "Composer Output: " . implode("\n", $composer_output) . "\n", FILE_APPEND);

// 9. (Optional) Run migrations, clear cache, etc
// exec('php artisan migrate');
// exec('php artisan config:cache');

// 10. Log deployment success
// file_put_contents($log_file, "Deployment successful at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

echo "Deployed successfully!";


?>



