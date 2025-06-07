package com.employeemanagement;

public class Employee {
    private int empId;
    private String firstName;
    private String lastName;
    private String email;
    private String phone;
    private String hireDate;
    private String jobTitle;
    private double salary;
    private int deptId;
    private int managerId;
    private String profilePic;
    private String status;
    
    // Constructors, getters, and setters
    public Employee() {}
    
    public Employee(int empId, String firstName, String lastName, String email, 
                   String phone, String hireDate, String jobTitle, double salary, 
                   int deptId, int managerId, String profilePic, String status) {
        this.empId = empId;
        this.firstName = firstName;
        this.lastName = lastName;
        this.email = email;
        this.phone = phone;
        this.hireDate = hireDate;
        this.jobTitle = jobTitle;
        this.salary = salary;
        this.deptId = deptId;
        this.managerId = managerId;
        this.profilePic = profilePic;
        this.status = status;
    }
    
    // Getters and Setters
    public int getEmpId() { return empId; }
    public void setEmpId(int empId) { this.empId = empId; }
    // ... (similar for all other fields)
}