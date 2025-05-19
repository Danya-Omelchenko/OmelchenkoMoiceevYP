<?
class SVOStatus {
    public $svoStatusID;
    public $studentID;
    public $documentBasis;
    public $statusStart;
    public $statusEnd;
    public $notes;

    public function __construct($data) {
        $this->svoStatusID = $data['SVOStatusID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->documentBasis = $data['DocumentBasis'] ?? '';
        $this->statusStart = $data['StatusStart'] ?? '';
        $this->statusEnd = $data['StatusEnd'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class SVOStatusManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addSVOStatus(SVOStatus $status) {
        if (empty($status->studentID) || empty($status->statusStart) || empty($status->statusEnd)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $query = "INSERT INTO SVOStatus (StudentID, DocumentBasis, StatusStart, StatusEnd, Notes) 
                  VALUES ('{$status->studentID}', '{$status->documentBasis}', '{$status->statusStart}', '{$status->statusEnd}', '{$status->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при добавлении статуса СВО: " . $this->conn->error);
        }

        return $result;
    }
}
?>