<?php
class SpecialNeedsStudent {
    public $specialNeedsStudentID;
    public $studentID;
    public $statusOrder;
    public $statusStart;
    public $statusEnd;
    public $notes;

    public function __construct($data) {
        $this->specialNeedsStudentID = $data['SpecialNeedsStudentID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->statusOrder = $data['StatusOrder'] ?? '';
        $this->statusStart = $data['StatusStart'] ?? '';
        $this->statusEnd = $data['StatusEnd'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class SpecialNeedsManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addSpecialNeedsStatus(SpecialNeedsStudent $specialNeeds) {
        if (empty($specialNeeds->studentID) || empty($specialNeeds->statusStart) || empty($specialNeeds->statusEnd)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $query = "INSERT INTO SpecialNeedsStudents (StudentID, StatusOrder, StatusStart, StatusEnd, Notes)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issss",
            $specialNeeds->studentID,
            $specialNeeds->statusOrder,
            $specialNeeds->statusStart,
            $specialNeeds->statusEnd,
            $specialNeeds->notes
        );

        return $stmt->execute();
    }

    public function editSpecialNeedsStatus(SpecialNeedsStudent $specialNeeds) {
        if (empty($specialNeeds->studentID) || empty($specialNeeds->statusStart) || empty($specialNeeds->statusEnd)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $query = "UPDATE SpecialNeedsStudents SET
                  StudentID = ?,
                  StatusOrder = ?,
                  StatusStart = ?,
                  StatusEnd = ?,
                  Notes = ?
                  WHERE SpecialNeedsStudentID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssi",
            $specialNeeds->studentID,
            $specialNeeds->statusOrder,
            $specialNeeds->statusStart,
            $specialNeeds->statusEnd,
            $specialNeeds->notes,
            $specialNeeds->specialNeedsStudentID
        );

        return $stmt->execute();
    }

    public function getSpecialNeedsStatuses($filters = []) {
        $query = "SELECT s.*, st.LastName, st.FirstName, st.MiddleName
                  FROM SpecialNeedsStudents s
                  LEFT JOIN Students st ON s.StudentID = st.StudentID
                  WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($filters['studentID'])) {
            $query .= " AND s.StudentID = ?";
            $params[] = $filters['studentID'];
            $types .= 'i';
        }

        if (!empty($filters['statusOrder'])) {
            $query .= " AND s.StatusOrder LIKE ?";
            $params[] = '%'.$filters['statusOrder'].'%';
            $types .= 's';
        }

        if (!empty($filters['statusStart'])) {
            $query .= " AND s.StatusStart = ?";
            $params[] = $filters['statusStart'];
            $types .= 's';
        }

        if (!empty($filters['statusEnd'])) {
            $query .= " AND s.StatusEnd = ?";
            $params[] = $filters['statusEnd'];
            $types .= 's';
        }

        if (!empty($filters['notes'])) {
            $query .= " AND s.Notes LIKE ?";
            $params[] = '%'.$filters['notes'].'%';
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $specialNeedsList = [];
        while ($row = $result->fetch_assoc()) {
            $specialNeedsList[] = $row;
        }

        return $specialNeedsList;
    }
}
?>
