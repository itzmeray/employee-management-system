<?php
session_start();
header('Content-Type: application/json');

// Include DB connection
require 'db.php';

// Utility function
function respond($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// REGISTER
if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Basic validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirmPassword)) {
        respond(false, "All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(false, "Invalid email format.");
    }

    if ($password !== $confirmPassword) {
        respond(false, "Passwords do not match.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        respond(false, "Email is already registered.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$fullname, $email, $hashedPassword])) {
        respond(true, "Registered successfully! Redirecting to login...");
    } else {
        respond(false, "Registration failed. Try again.");
    }
}

// LOGIN
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        respond(false, "Email and password are required.");
    }

    $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            respond(true, "Login successful! Redirecting to dashboard...");
        } else {
            respond(false, "Incorrect password.");
        }
    } else {
        respond(false, "Email not found.");
    }
}

respond(false, "Invalid request.");
?>
