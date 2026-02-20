<?php
/**
 * Newsletter Signup — PHP Bridge to Mailchimp
 *
 * Receives POST from the Astro newsletter form, validates, rate-limits,
 * and adds the email to a Mailchimp audience via their API.
 *
 * No Composer dependencies — just cURL (built into PHP on Krystal).
 *
 * SETUP PER CLIENT:
 * 1. Get Mailchimp API key: Account → Extras → API keys
 * 2. Get List/Audience ID: Audience → Settings → Audience name and defaults
 * 3. Server prefix is the last part of your API key (e.g., "us21" from "abc123-us21")
 * 4. Update credentials below
 * 5. Set ALLOWED_ORIGIN to the live domain
 * 6. Upload to ~/public_html/api/ on Krystal
 *
 * DOUBLE OPT-IN:
 * status_if_new is set to 'pending' — Mailchimp sends a confirmation email
 * before adding the subscriber. This is the correct default for GDPR / UK
 * privacy compliance. Change to 'subscribed' only if you have explicit
 * consent elsewhere (e.g., a checkbox that says "I agree to receive emails").
 */

// ============================================================
// ⚙️ CONFIGURATION — change these per client
// ============================================================

// Mailchimp — get these from the client's Mailchimp account
define('MAILCHIMP_API_KEY', 'your-api-key-here');           // e.g., abc123def456-us21
define('MAILCHIMP_SERVER_PREFIX', 'us21');                   // last part of API key after the dash
define('MAILCHIMP_LIST_ID', 'your-list-id-here');           // Audience → Settings → Audience ID

// Security
define('ALLOWED_ORIGIN', 'https://clientdomain.com');
define('RATE_LIMIT_SECONDS', 30);                           // shorter than contact — newsletter is low-friction

// ============================================================
// 🚫 DO NOT EDIT BELOW THIS LINE (unless you know what you're doing)
// ============================================================

// ------------------------------------
// 0. Build redirect URLs from referer
//    (newsletter can appear on any page)
// ------------------------------------
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$redirectBase = '';

if (!empty($referer) && strpos($referer, ALLOWED_ORIGIN) === 0) {
    // Strip any existing fragment/query from the referer
    $redirectBase = strtok($referer, '?#');
} else {
    // Fallback to homepage
    $redirectBase = ALLOWED_ORIGIN . '/';
}

$successRedirect = $redirectBase . '#newsletter-success';
$errorRedirect   = $redirectBase . '?newsletter=error#newsletter-error';

// ------------------------------------
// 1. Only accept POST
// ------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

// ------------------------------------
// 2. Origin / Referer check
//    Blocks submissions from other domains
// ------------------------------------
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$originAllowed  = !empty($origin) && strpos($origin, ALLOWED_ORIGIN) === 0;
$refererAllowed = !empty($referer) && strpos($referer, ALLOWED_ORIGIN) === 0;

if (!$originAllowed && !$refererAllowed) {
    http_response_code(403);
    exit('Forbidden.');
}

// ------------------------------------
// 3. Honeypot check
//    The form includes a hidden "website" field that humans never fill in.
//    If it has a value, it's a bot — reject silently.
// ------------------------------------
$honeypot = trim($_POST['website'] ?? '');
if ($honeypot !== '') {
    // Fake success — don't let the bot know it was caught
    header('Location: ' . $successRedirect, true, 303);
    exit;
}

// ------------------------------------
// 4. Rate limiting (1 per IP per 30 seconds)
//    Uses temp files. No database needed.
// ------------------------------------
$rateLimitDir = sys_get_temp_dir() . '/newsletter_ratelimit';
if (!is_dir($rateLimitDir)) {
    mkdir($rateLimitDir, 0700, true);
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ipHash = md5($ip);  // Don't store raw IPs
$rateLimitFile = $rateLimitDir . '/' . $ipHash;

if (file_exists($rateLimitFile)) {
    $lastSubmission = (int) file_get_contents($rateLimitFile);
    if (time() - $lastSubmission < RATE_LIMIT_SECONDS) {
        http_response_code(429);
        exit('Please wait before submitting again.');
    }
}

// ------------------------------------
// 5. Input validation
// ------------------------------------
$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 320) {
    header('Location: ' . $errorRedirect, true, 303);
    exit;
}

// ------------------------------------
// 6. Call Mailchimp API
//    PUT /lists/{list_id}/members/{subscriber_hash}
//    This is an "add or update" — safe to call for existing subscribers.
//    subscriber_hash = md5(lowercase email)
// ------------------------------------
$subscriberHash = md5(strtolower($email));
$apiUrl = sprintf(
    'https://%s.api.mailchimp.com/3.0/lists/%s/members/%s',
    MAILCHIMP_SERVER_PREFIX,
    MAILCHIMP_LIST_ID,
    $subscriberHash
);

$payload = json_encode([
    'email_address' => strtolower($email),
    'status_if_new' => 'pending',  // double opt-in — see note at top
]);

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_TIMEOUT        => 10,  // 10 second timeout
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . MAILCHIMP_API_KEY,
        'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => $payload,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// ------------------------------------
// 7. Handle response
// ------------------------------------
if ($curlError) {
    error_log('Newsletter signup cURL error: ' . $curlError);
    header('Location: ' . $errorRedirect, true, 303);
    exit;
}

if ($httpCode >= 200 && $httpCode < 300) {
    // Record this IP for rate limiting (only on success)
    file_put_contents($rateLimitFile, (string) time());

    header('Location: ' . $successRedirect, true, 303);
    exit;
}

// Mailchimp returned an error — log it for debugging
$decoded = json_decode($response, true);
$mcError = $decoded['detail'] ?? $decoded['title'] ?? 'Unknown Mailchimp error';
error_log(sprintf(
    'Newsletter signup Mailchimp error (HTTP %d): %s — email: %s',
    $httpCode,
    $mcError,
    $email
));

// Special case: "Member Exists" with status "subscribed" is actually fine
// (they're already on the list — treat as success)
if ($httpCode === 400 && isset($decoded['title']) && $decoded['title'] === 'Member Exists') {
    file_put_contents($rateLimitFile, (string) time());
    header('Location: ' . $successRedirect, true, 303);
    exit;
}

header('Location: ' . $errorRedirect, true, 303);
exit;