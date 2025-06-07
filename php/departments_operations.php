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

try {
    $conn->beginTransaction();

    // Handle GET request - Fetch all departments with manager info
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $conn->query("
            SELECT d.*, e.name as manager_name, 
                   (SELECT COUNT(*) FROM employees1 WHERE department = d.name) as employee_count
            FROM departments1 d
            LEFT JOIN employees1 e ON d.manager_id = e.id
            ORDER BY d.name
        ");
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $departments,
            'count' => count($departments)
        ]);
        exit;
    }

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Add new department
        if (isset($_POST['add_department'])) {
            if (empty($_POST['name'])) {
                throw new Exception("Department name is required");
            }

            $name = sanitizeInput($_POST['name']);
            $manager_id = !empty($_POST['manager_id']) ? (int)$_POST['manager_id'] : null;
            $location = !empty($_POST['location']) ? sanitizeInput($_POST['location']) : null;
            $budget = !empty($_POST['budget']) ? (float)$_POST['budget'] : 0.00;

            // Check if department exists
            $stmt = $conn->prepare("SELECT id FROM departments1 WHERE name = ?");
            $stmt->execute([$name]);
            
            if ($stmt->rowCount() > 0) {
                throw new Exception("Department '$name' already exists");
            }

            // Validate manager exists if provided
            if ($manager_id) {
                $stmt = $conn->prepare("SELECT id FROM employees1 WHERE id = ?");
                $stmt->execute([$manager_id]);
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception("Selected manager does not exist");
                }
            }

            // Insert department
            $stmt = $conn->prepare("
                INSERT INTO departments1 (name, manager_id, location, budget)
                VALUES (:name, :manager_id, :location, :budget)
            ");
            
            $stmt->execute([
                ':name' => $name,
                ':manager_id' => $manager_id,
                ':location' => $location,
                ':budget' => $budget
            ]);

            $conn->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Department added successfully',
                'id' => $conn->lastInsertId()
            ]);
            exit;
        }
        
        // Delete department
        if (isset($_POST['delete_department'])) {
            if (empty($_POST['id'])) {
                throw new Exception("Department ID is required");
            }
            
            $id = (int)$_POST['id'];
            
            if ($id <= 0) {
                throw new Exception("Invalid department ID");
            }
            
            // Get department name first
            $stmt = $conn->prepare("SELECT name FROM departments1 WHERE id = ?");
            $stmt->execute([$id]);
            $department = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$department) {
                throw new Exception("Department not found");
            }
            
            // Update employees in this department to 'Unassigned'
            $updateStmt = $conn->prepare("UPDATE employees1 SET department = 'Unassigned' WHERE department = ?");
            $updateStmt->execute([$department['name']]);
            
            // Delete the department
            $stmt = $conn->prepare("DELETE FROM departments1 WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("No department was deleted");
            }
            
            $conn->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Department deleted successfully',
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