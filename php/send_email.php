<?php
header('Content-Type: application/json');

// Validate and sanitize input
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
$recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_EMAIL);

// Validate required fields
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}

// Set the recipient email (use the one from form or default)
$to = !empty($recipient) ? $recipient : 'bkiranshetty2003@gmail.com';

// Email headers
$headers = "From: $name <$email>" . "\r\n";
$headers .= "Reply-To: $email" . "\r\n";
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";

// Email content
$email_content = "
    <html>
    <head>
        <title>$subject</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #4a6bff; color: white; padding: 10px 20px; border-radius: 5px 5px 0 0; }
            .content { padding: 20px; background-color: #f9f9f9; border-radius: 0 0 5px 5px; }
            .footer { margin-top: 20px; font-size: 0.8em; color: #777; text-align: center; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>$subject</h2>
            </div>
            <div class='content'>
                <p><strong>From:</strong> $name ($email)</p>
                <p><strong>Message:</strong></p>
                <p>".nl2br($message)."</p>
            </div>
            <div class='footer'>
                <p>This email was sent from the contact form on Employee Management System</p>
            </div>
        </div>
    </body>
    </html>
";

// Send email
$mailSent = mail($to, $subject, $email_content, $headers);

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
}
?>