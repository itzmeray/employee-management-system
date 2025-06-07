<?php
require_once 'db.php';

header('Content-Type: application/json');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

try {
    $conn->beginTransaction();

    // Handle GET request - Fetch all employees
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $conn->query("SELECT id, name, email, department, job_title, status, profile_img FROM employees1");
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $employees,
            'count' => count($employees)
        ]);
        exit;
    }

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Add new employee
        if (isset($_POST['add_employee'])) {
            $required = ['name', 'email', 'department', 'job_title'];
            $errors = [];
            
            // Validate required fields
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $errors[] = "$field is required";
                }
            }
            
            if (!empty($errors)) {
                throw new Exception(implode(', ', $errors));
            }
            
            $name = sanitizeInput($_POST['name']);
            $email = sanitizeInput($_POST['email']);
            $department = sanitizeInput($_POST['department']);
            $job_title = sanitizeInput($_POST['job_title']);
            $status = isset($_POST['status']) ? sanitizeInput($_POST['status']) : 'Active';
            
            // Validate email
            if (!isValidEmail($email)) {
                throw new Exception("Invalid email format");
            }
            
            // Check if email exists
            $stmt = $conn->prepare("SELECT id FROM employees1 WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                throw new Exception("Email already exists");
            }
            
            // Handle file upload
            $profile_img = null;
            if (!empty($_FILES['profile_img']['name'])) {
                $uploadDir = '../assets/images/profiles/';
                
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        throw new Exception("Failed to create upload directory");
                    }
                }
                
                $fileInfo = pathinfo($_FILES['profile_img']['name']);
                $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9-_\.]/', '', $fileInfo['filename']);
                $fileName .= '.' . strtolower($fileInfo['extension']);
                $targetPath = $uploadDir . $fileName;
                
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed");
                }
                
                if ($_FILES['profile_img']['size'] > 5000000) { // 5MB limit
                    throw new Exception("File is too large (max 5MB)");
                }
                
                if (!getimagesize($_FILES['profile_img']['tmp_name'])) {
                    throw new Exception("File is not a valid image");
                }
                
                if (!move_uploaded_file($_FILES['profile_img']['tmp_name'], $targetPath)) {
                    throw new Exception("Failed to upload image");
                }
                
                $profile_img = 'assets/images/profiles/' . $fileName;
            }
            
            // Insert employee
            $stmt = $conn->prepare("
                INSERT INTO employees1 (name, email, department, job_title, status, profile_img)
                VALUES (:name, :email, :department, :job_title, :status, :profile_img)
            ");
            
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':department' => $department,
                ':job_title' => $job_title,
                ':status' => $status,
                ':profile_img' => $profile_img
            ]);
            
            $conn->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Employee added successfully',
                'id' => $conn->lastInsertId(),
                'profile_img' => $profile_img
            ]);
            exit;
        }
        
        // Delete employee
        if (isset($_POST['delete_employee'])) {
            if (empty($_POST['id'])) {
                throw new Exception("Employee ID is required");
            }
            
            $id = (int)$_POST['id'];
            
            if ($id <= 0) {
                throw new Exception("Invalid employee ID");
            }
            
            // Get employee data first
            $stmt = $conn->prepare("SELECT profile_img FROM employees1 WHERE id = ?");
            $stmt->execute([$id]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$employee) {
                throw new Exception("Employee not found");
            }
            
            // Delete profile image if exists
            if (!empty($employee['profile_img'])) {
                $imagePath = '../' . $employee['profile_img'];
                if (file_exists($imagePath)) {
                    if (!unlink($imagePath)) {
                        throw new Exception("Failed to delete profile image");
                    }
                }
            }
            
            // Delete employee record
            $stmt = $conn->prepare("DELETE FROM employees1 WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("No employee was deleted");
            }
            
            $conn->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Employee deleted successfully',
                'id' => $id
            ]);
            exit;
        }
        
        throw new Exception("Invalid action");
    }
    
    throw new Exception("Method not allowed");
    
} catch (PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'code' => $e->getCode()
    ]);
} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}
?>