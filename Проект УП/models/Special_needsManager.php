<?
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
                  VALUES ('{$specialNeeds->studentID}', '{$specialNeeds->statusOrder}', '{$specialNeeds->statusStart}', '{$specialNeeds->statusEnd}', '{$specialNeeds->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при добавлении статуса ОВЗ: " . $this->conn->error);
        }

        return $result;
    }
}
?>