package com.employeemanagement;

import java.sql.*;

public class EmployeeDAO {
    private Connection conn;
    
    public EmployeeDAO() throws SQLException {
        // Hostinger MySQL connection
        this.conn = DriverManager.getConnection(
            "jdbc:mysql://localhost/u443763129_KiranProject",
            "u443763129_KiranProject",
            "Bkiru@2003"
        );
    }
    
    public String addEmployee(String firstName, String lastName, String email, 
                            String phone, String hireDate, String jobTitle,
                            double salary, int deptId, int managerId) {
        try {
            PreparedStatement stmt = conn.prepareStatement(
                "INSERT INTO employees (...) VALUES (?,?,?,?,?,?,?,?,?)");
            // Set parameters
            stmt.executeUpdate();
            return "Employee added successfully";
        } catch (SQLException e) {
            return "Error: " + e.getMessage();
        }
    }
    
    // Other CRUD methods...
}