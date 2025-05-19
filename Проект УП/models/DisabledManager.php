<?php class DisabledStudent {
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
        if (empty($disabledStudent->studentID) || empty($disabledStudent->statusStart) || empty($disabledStudent->statusEnd)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $query = "INSERT INTO DisabledStudents (StudentID, StatusOrder, StatusStart, StatusEnd, DisabilityType, Notes) 
                  VALUES ('{$disabledStudent->studentID}', '{$disabledStudent->statusOrder}', '{$disabledStudent->statusStart}', '{$disabledStudent->statusEnd}', '{$disabledStudent->disabilityType}', '{$disabledStudent->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при добавлении: " . $this->conn->error);
        }

        return $result;
    }

    // Другие методы по аналогии
}
?>