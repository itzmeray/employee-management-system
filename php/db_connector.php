<?php
// Hostinger MySQL connection (using your credentials)
$host = "localhost";       // Hostinger's MySQL host
$dbname = "u443763129_KiranProject";
$username = "u443763129_KiranProject";
$password = "Bkiru@2003";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>