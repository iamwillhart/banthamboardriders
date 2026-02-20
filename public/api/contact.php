<?php
/**
 * Contact Form — PHP Bridge
 *
 * Receives POST from the Astro contact form, validates, rate-limits,
 * and sends via SMTP using PHPMailer.
 *
 * SETUP PER CLIENT:
 * 1. Update SMTP credentials below
 * 2. Set ALLOWED_ORIGIN to the live domain
 * 3. Set RECIPIENT_EMAIL to the client's inbox
 * 4. SSH into Krystal: cd ~/public_html/api && composer install --no-dev
 */

// ============================================================
// ⚙️ CONFIGURATION — change these per client
// ============================================================

// SMTP — Google Workspace
// define('SMTP_HOST', 'smtp.gmail.com');
// define('SMTP_PORT', 587);
// define('SMTP_USER', 'noreply@clientdomain.com');
// define('SMTP_PASS', 'app-password-here');

// SMTP — Krystal cPanel email
define('SMTP_HOST', 'mail.clientdomain.com');
define('SMTP_PORT', 465);
define('SMTP_ENCRYPTION', 'ssl');        // 'ssl' for 465, 'tls' for 587
define('SMTP_USER', 'noreply@clientdomain.com');
define('SMTP_PASS', 'password-here');

// Recipient
define('RECIPIENT_EMAIL', 'hello@clientdomain.com');
define('RECIPIENT_NAME', 'Client Name');

// From address (must match SMTP_USER on most hosts)
define('FROM_EMAIL', SMTP_USER);
define('FROM_NAME', 'Website Contact Form');

// Security
define('ALLOWED_ORIGIN', 'https://clientdomain.com');
define('RATE_LIMIT_SECONDS', 60);

// Redirect URLs (after submission)
define('SUCCESS_REDIRECT', ALLOWED_ORIGIN . '/contact#form-success');
define('ERROR_REDIRECT', ALLOWED_ORIGIN . '/contact?error=1');

// ============================================================
// 🚫 DO NOT EDIT BELOW THIS LINE (unless you know what you're doing)
// ============================================================

// Load PHPMailer
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
$referer = $_SERVER['HTTP_REFERER'] ?? '';

$originAllowed = !empty($origin) && strpos($origin, ALLOWED_ORIGIN) === 0;
$refererAllowed = !empty($referer) && strpos($referer, ALLOWED_ORIGIN) === 0;

if (!$originAllowed && !$refererAllowed) {
    http_response_code(403);
    exit('Forbidden.');
}

// ------------------------------------
// 3. Honeypot check
//    The form includes a hidden field that humans never fill in.
//    If it has a value, it's a bot — reject silently.
// ------------------------------------
$honeypot = trim($_POST['company'] ?? '');
if ($honeypot !== '') {
    // Fake success — don't let the bot know it was caught
    header('Location: ' . SUCCESS_REDIRECT, true, 303);
    exit;
}

// ------------------------------------
// 4. Rate limiting (1 per IP per 60 seconds)
//    Uses temp files. No database needed.
// ------------------------------------
$rateLimitDir = sys_get_temp_dir() . '/contact_ratelimit';
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
// 5. Input validation & sanitisation
// ------------------------------------
$name    = trim(strip_tags($_POST['name'] ?? ''));
$email   = trim($_POST['email'] ?? '');
$message = trim(strip_tags($_POST['message'] ?? ''));

$errors = [];

if ($name === '' || mb_strlen($name) > 200) {
    $errors[] = 'Name is required (max 200 characters).';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 320) {
    $errors[] = 'A valid email address is required.';
}

if ($message === '' || mb_strlen($message) > 5000) {
    $errors[] = 'Message is required (max 5000 characters).';
}

if (!empty($errors)) {
    http_response_code(422);
    header('Location: ' . ERROR_REDIRECT, true, 303);
    exit;
}

// ------------------------------------
// 6. Send via PHPMailer (SMTP)
// ------------------------------------
$mail = new PHPMailer(true);

try {
    // SMTP config
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';

    // Addresses
    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress(RECIPIENT_EMAIL, RECIPIENT_NAME);
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'Contact form: ' . mb_substr($name, 0, 60);
    $mail->Body    = implode("\n", [
        'Name:    ' . $name,
        'Email:   ' . $email,
        '',
        'Message:',
        '---',
        $message,
        '---',
        '',
        'Sent from: ' . ALLOWED_ORIGIN,
        'IP: ' . $ip,
        'Time: ' . date('Y-m-d H:i:s T'),
    ]);

    $mail->send();

    // Record this IP for rate limiting
    file_put_contents($rateLimitFile, time());

    // Redirect to thank-you page
    header('Location: ' . SUCCESS_REDIRECT, true, 303);
    exit;

} catch (Exception $e) {
    error_log('Contact form error: ' . $mail->ErrorInfo);
    header('Location: ' . ERROR_REDIRECT, true, 303);
    exit;
}