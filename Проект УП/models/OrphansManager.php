<?
class Orphan {
    public $orphanID;
    public $studentID;
    public $statusOrder;
    public $statusStart;
    public $statusEnd;
    public $notes;

    public function __construct($data) {
        $this->orphanID = $data['OrphanID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->statusOrder = $data['StatusOrder'] ?? '';
        $this->statusStart = $data['StatusStart'] ?? '';
        $this->statusEnd = $data['StatusEnd'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class OrphanManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addOrphanStatus(Orphan $orphan) {
        if (empty($orphan->studentID) || empty($orphan->statusStart) || empty($orphan->statusEnd)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $query = "INSERT INTO Orphans (StudentID, StatusOrder, StatusStart, StatusEnd, Notes) 
                  VALUES ('{$orphan->studentID}', '{$orphan->statusOrder}', '{$orphan->statusStart}', '{$orphan->statusEnd}', '{$orphan->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при добавлении: " . $this->conn->error);
        }

        return $result;
    }
}?>