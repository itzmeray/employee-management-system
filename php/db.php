<?php
$host = "localhost";
$username = "u443763129_KiranProject";
$password = "Bkiru@2003";
$dbname = "u443763129_KiranProject";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
