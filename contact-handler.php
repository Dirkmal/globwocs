<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// Sanitise inputs
$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$phone   = trim(strip_tags($_POST['phone']   ?? ''));
$org     = trim(strip_tags($_POST['organisation'] ?? ''));
$service = trim(strip_tags($_POST['service'] ?? ''));
$budget  = trim(strip_tags($_POST['budget']  ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Basic validation
if (!$name || !$email || !$message) {
    echo json_encode(['ok' => false, 'error' => 'Please fill in all required fields.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

// Build email
$to      = 'contactus@globwocs.com';
$subject = 'New Project Enquiry — ' . $name . ($org ? " ({$org})" : '');
$body    = "New enquiry via the Globwocs website\n";
$body   .= str_repeat('─', 50) . "\n\n";
$body   .= "Name:         {$name}\n";
$body   .= "Email:        {$email}\n";
if ($phone)   $body .= "Phone:        {$phone}\n";
if ($org)     $body .= "Organisation: {$org}\n";
if ($service) $body .= "Service:      {$service}\n";
if ($budget)  $body .= "Budget:       {$budget}\n";
$body   .= "\nProject Brief:\n{$message}\n";

$headers  = "From: noreply@globwocs.com\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . PHP_VERSION;

$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    echo json_encode(['ok' => true]);
} else {
    // Log and return friendly error
    error_log("Globwocs contact form: mail() failed for {$email}");
    echo json_encode(['ok' => false, 'error' => 'There was an issue sending your message. Please email us directly at contactus@globwocs.com']);
}
