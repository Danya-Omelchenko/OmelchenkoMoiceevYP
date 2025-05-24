<?php
class Department {
    public $departmentID;
    public $departmentName;

    public function __construct($data) {
        $this->departmentID = $data['DepartmentID'] ?? null;
        $this->departmentName = $data['DepartmentName'] ?? '';
    }
}

class DepartmentsManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addDepartment(Department $department) {
        if (!isset($department->departmentName) || $department->departmentName === '') {
            throw new Exception("Обязательное поле 'departmentName' не заполнено");
        }

        $query = "INSERT INTO Departments (DepartmentName) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $department->departmentName);

        return $stmt->execute();
    }

    public function editDepartment(Department $department) {
        if (!isset($department->departmentName) || $department->departmentName === '') {
            throw new Exception("Обязательное поле 'departmentName' не заполнено");
        }

        $query = "UPDATE Departments SET DepartmentName = ? WHERE DepartmentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $department->departmentName, $department->departmentID);

        return $stmt->execute();
    }

    public function deleteDepartment($departmentID) {
        $query = "DELETE FROM Departments WHERE DepartmentID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $departmentID);

        return $stmt->execute();
    }

    public function getDepartments($filters = []) {
        $query = "SELECT * FROM Departments WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($filters['departmentName'])) {
            $query .= " AND DepartmentName LIKE ?";
            $params[] = '%'.$filters['departmentName'].'%';
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }

        return $departments;
    }
}
?>