<?php
header('Content-Type: application/json');

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Validate data
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Please complete all required fields correctly."]);
        exit;
    }

    // Set recipient email
    $recipient = "bkiran2003@gmail.com";
    
    // Set email subject
    $email_subject = "New Contact Form Submission: $subject";

    // Build email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n\n";
    $email_content .= "Subject: $subject\n\n";
    $email_content .= "Message:\n$message\n";

    // Build email headers
    $email_headers = "From: $name <$email>";

    // Send email
    if (mail($recipient, $email_subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Thank you! Your message has been sent successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Oops! Something went wrong. Please try again later."]);
    }
} else {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "There was a problem with your submission. Please try again."]);
}
?>