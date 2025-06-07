public class EmployeeServlet extends HttpServlet {
    private EmployeeDAO employeeDAO;

    public void init() {
        employeeDAO = new EmployeeDAO(DBConnection.getConnection());
    }
protected void doGet(HttpServletRequest request, HttpServletResponse response)
        throws ServletException, IOException {
    String action = request.getParameter("action");

    switch (action) {
        case "new":
            showNewForm(request, response);
            break;
        case "insert":
            insertEmployee(request, response);
            break;
        case "delete":
            deleteEmployee(request, response);
            break;
        case "edit":
            showEditForm(request, response);
            break;
        case "update":
            updateEmployee(request, response);
            break;
        default:
            listEmployees(request, response);
            break;
    }
}
private void listEmployees(HttpServletRequest request, HttpServletResponse response)
        throws ServletException, IOException {
    List<Employee> list = employeeDAO.getAllEmployees();
    request.setAttribute("employeeList", list);
    RequestDispatcher dispatcher = request.getRequestDispatcher("employee-list.jsp");
    dispatcher.forward(request, response);
}
private void listEmployees(HttpServletRequest request, HttpServletResponse response)
        throws ServletException, IOException {
    List<Employee> list = employeeDAO.getAllEmployees();
    request.setAttribute("employeeList", list);
    RequestDispatcher dispatcher = request.getRequestDispatcher("employee-list.jsp");
    dispatcher.forward(request, response);
}
