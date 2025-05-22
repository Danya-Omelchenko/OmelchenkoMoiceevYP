<?php
class DisabledStudent {
    public $disabledStudentID;
    public $studentID;
    public $statusOrder;
    public $statusStart;
    public $statusEnd;
    public $disabilityType;
    public $notes;

    public function __construct($data) {
        $this->disabledStudentID = $data['DisabledStudentID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->statusOrder = $data['StatusOrder'] ?? '';
        $this->statusStart = $data['StatusStart'] ?? '';
        $this->statusEnd = $data['StatusEnd'] ?? '';
        $this->disabilityType = $data['DisabilityType'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class DisabledStudentManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addDisabledStudent(DisabledStudent $disabledStudent) {
        $requiredFields = ['studentID', 'statusStart', 'statusEnd'];
        foreach ($requiredFields as $field) {
            if (empty($disabledStudent->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO DisabledStudents (StudentID, StatusOrder, StatusStart, StatusEnd, DisabilityType, Notes)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssss",
            $disabledStudent->studentID,
            $disabledStudent->statusOrder,
            $disabledStudent->statusStart,
            $disabledStudent->statusEnd,
            $disabledStudent->disabilityType,
            $disabledStudent->notes
        );

        return $stmt->execute();
    }

    public function editDisabledStudent(DisabledStudent $disabledStudent) {
        $requiredFields = ['studentID', 'statusStart', 'statusEnd'];
        foreach ($requiredFields as $field) {
            if (empty($disabledStudent->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE DisabledStudents SET
                  StudentID = ?,
                  StatusOrder = ?,
                  StatusStart = ?,
                  StatusEnd = ?,
                  DisabilityType = ?,
                  Notes = ?
                  WHERE DisabledStudentID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssssi",
            $disabledStudent->studentID,
            $disabledStudent->statusOrder,
            $disabledStudent->statusStart,
            $disabledStudent->statusEnd,
            $disabledStudent->disabilityType,
            $disabledStudent->notes,
            $disabledStudent->disabledStudentID
        );

        return $stmt->execute();
    }

    public function getDisabledStudents($filters = []) {
    $query = "SELECT ds.*, s.LastName, s.FirstName, s.MiddleName
              FROM DisabledStudents ds
              LEFT JOIN Students s ON ds.StudentID = s.StudentID
              WHERE 1=1";

    $params = [];
    $types = '';

    if (!empty($filters['studentID'])) {
        $query .= " AND ds.StudentID = ?";
        $params[] = $filters['studentID'];
        $types .= 'i';
    }

    if (!empty($filters['statusOrder'])) {
        $query .= " AND ds.StatusOrder LIKE ?";
        $params[] = '%'.$filters['statusOrder'].'%';
        $types .= 's';
    }

    if (!empty($filters['statusStart'])) {
        // Проверяем, является ли значение корректной датой
        if (DateTime::createFromFormat('Y-m-d', $filters['statusStart']) !== false) {
            $query .= " AND ds.StatusStart = ?";
            $params[] = $filters['statusStart'];
            $types .= 's';
        } else {
            // Можно добавить обработку ошибки или логирование
            error_log("Invalid statusStart date format: " . $filters['statusStart']);
        }
    }

    if (!empty($filters['statusEnd'])) {
        // Проверяем, является ли значение корректной датой
        if (DateTime::createFromFormat('Y-m-d', $filters['statusEnd']) !== false) {
            $query .= " AND ds.StatusEnd = ?";
            $params[] = $filters['statusEnd'];
            $types .= 's';
        } else {
            // Можно добавить обработку ошибки или логирование
            error_log("Invalid statusEnd date format: " . $filters['statusEnd']);
        }
    }

    if (!empty($filters['disabilityType'])) {
        $query .= " AND ds.DisabilityType LIKE ?";
        $params[] = '%'.$filters['disabilityType'].'%';
        $types .= 's';
    }

    if (!empty($filters['notes'])) {
        $query .= " AND ds.Notes LIKE ?";
        $params[] = '%'.$filters['notes'].'%';
        $types .= 's';
    }

    // Добавляем сортировку по умолчанию
    $query .= " ORDER BY s.LastName, s.FirstName";

    $stmt = $this->conn->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    return $students;
}
}
?>