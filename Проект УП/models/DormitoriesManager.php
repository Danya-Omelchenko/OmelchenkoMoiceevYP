<?php
class Dormitory {
    public $dormitoryID;
    public $studentID;
    public $roomNumber;
    public $checkInDate;
    public $checkOutDate;
    public $notes;

    public function __construct($data) {
        $this->dormitoryID = $data['DormitoryID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->roomNumber = $data['RoomNumber'] ?? '';
        $this->checkInDate = $data['CheckInDate'] ?? '';
        $this->checkOutDate = $data['CheckOutDate'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class DormitoryManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function assignToDormitory(Dormitory $dormitory) {
        $requiredFields = ['studentID', 'roomNumber', 'checkInDate'];
        foreach ($requiredFields as $field) {
            if (empty($dormitory->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO Dormitories (StudentID, RoomNumber, CheckInDate, CheckOutDate, Notes)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issss",
            $dormitory->studentID,
            $dormitory->roomNumber,
            $dormitory->checkInDate,
            $dormitory->checkOutDate,
            $dormitory->notes
        );

        return $stmt->execute();
    }

    public function editInDormitory(Dormitory $dormitory) {
        $requiredFields = ['studentID', 'roomNumber', 'checkInDate'];
        foreach ($requiredFields as $field) {
            if (empty($dormitory->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE Dormitories SET
                  StudentID = ?,
                  RoomNumber = ?,
                  CheckInDate = ?,
                  CheckOutDate = ?,
                  Notes = ?
                  WHERE DormitoryID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssi",
            $dormitory->studentID,
            $dormitory->roomNumber,
            $dormitory->checkInDate,
            $dormitory->checkOutDate,
            $dormitory->notes,
            $dormitory->dormitoryID
        );

        return $stmt->execute();
    }
    public function getDormitories($filters = []) {
        $query = "SELECT d.*, s.LastName, s.FirstName, s.MiddleName
                  FROM Dormitories d
                  LEFT JOIN Students s ON d.StudentID = s.StudentID
                  WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($filters['studentID'])) {
            $query .= " AND d.StudentID = ?";
            $params[] = $filters['studentID'];
            $types .= 'i';
        }

        if (!empty($filters['roomNumber'])) {
            $query .= " AND d.RoomNumber LIKE ?";
            $params[] = '%'.$filters['roomNumber'].'%';
            $types .= 's';
        }

        if (!empty($filters['checkInDate'])) {
            $query .= " AND d.CheckInDate = ?";
            $params[] = $filters['checkInDate'];
            $types .= 's';
        }

        if (!empty($filters['checkOutDate'])) {
            $query .= " AND d.CheckOutDate = ?";
            $params[] = $filters['checkOutDate'];
            $types .= 's';
        }

        if (!empty($filters['notes'])) {
            $query .= " AND d.Notes LIKE ?";
            $params[] = '%'.$filters['notes'].'%';
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $dormitories = [];
        while ($row = $result->fetch_assoc()) {
            $dormitories[] = $row;
        }

        return $dormitories;
    }
}
?>