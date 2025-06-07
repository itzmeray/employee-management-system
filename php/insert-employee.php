<?php
$host = "localhost";
$username = "u443763129_KiranProject";
$password = "Bkiru@2003";
$dbname = "u443763129_KiranProject";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $job_title = $_POST['job_title'];
    $status = $_POST['status'];

    // Handle image upload
    $imagePath = 'uploads/' . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $imagePath);

    // Insert into DB
    $sql = "INSERT INTO employees1 (name, email, department, job_title, status, profile_image)
            VALUES (:name, :email, :department, :job_title, :status, :profile_image)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':department' => $department,
        ':job_title' => $job_title,
        ':status' => $status,
        ':profile_image' => $imagePath
    ]);

    header("Location: employees.php");
    exit();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
