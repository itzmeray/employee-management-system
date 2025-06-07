
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class DepartmentDAO {
    private Connection conn;

    public DepartmentDAO(Connection conn) {
        this.conn = conn;
    }

    // Create new department
    public boolean insertDepartment(Department dept) {
        String sql = "INSERT INTO departments (name) VALUES (?)";
        try (PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, dept.getName());
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    // Read all departments
    public List<Department> getAllDepartments() {
        List<Department> departments = new ArrayList<>();
        String sql = "SELECT * FROM departments";
        try (Statement stmt = conn.createStatement(); ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                Department dept = new Department();
                dept.setId(rs.getInt("id"));
                dept.setName(rs.getString("name"));
                departments.add(dept);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return departments;
    }

    // Read single department by ID
    public Department getDepartmentById(int id) {
        String sql = "SELECT * FROM departments WHERE id = ?";
        try (PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                Department dept = new Department();
                dept.setId(rs.getInt("id"));
                dept.setName(rs.getString("name"));
                return dept;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    // Update department
    public boolean updateDepartment(Department dept) {
        String sql = "UPDATE departments SET name = ? WHERE id = ?";
        try (PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, dept.getName());
            stmt.setInt(2, dept.getId());
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    // Delete department
    public boolean deleteDepartment(int id) {
        String sql = "DELETE FROM departments WHERE id = ?";
        try (PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }
}
