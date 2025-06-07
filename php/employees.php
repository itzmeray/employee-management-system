<?php
$host = "localhost";
$username = "u443763129_KiranProject";
$password = "Bkiru@2003";
$dbname = "u443763129_KiranProject";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM employees1");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <!-- Sidebar content -->
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>All Employees</h1>
                <a href="add-employee.html" class="btn primary">Add Employee</a>
            </header>

            <div class="dashboard-content">
                <div class="table-container">
                    <table id="employees-table" class="display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Job Title</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td><?= htmlspecialchars($emp['id']) ?></td>
                                <td>
                                    <div class="employee-info">
                                        <img src="<?= htmlspecialchars($emp['profile_image']) ?>" alt="Employee" style="width:40px;height:40px;border-radius:50%;">
                                        <span><?= htmlspecialchars($emp['name']) ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($emp['email']) ?></td>
                                <td><?= htmlspecialchars($emp['department']) ?></td>
                                <td><?= htmlspecialchars($emp['job_title']) ?></td>
                                <td>
                                    <span class="status-badge <?= strtolower($emp['status']) ?>"><?= htmlspecialchars($emp['status']) ?></span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-employee.php?id=<?= $emp['id'] ?>" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                        <a href="delete-employee.php?id=<?= $emp['id'] ?>" onclick="return confirm('Are you sure?');" class="btn-icon delete"><i class="fas fa-trash"></i></a>
                                        <a href="profile.php?id=<?= $emp['id'] ?>" class="btn-icon view"><i class="fas fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#employees-table').DataTable();
        });
    </script>
</body>
</html>
