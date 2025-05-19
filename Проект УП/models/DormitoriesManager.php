<?
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
        $this->roomNumber = $data['RoomNumber'] ?? null;
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
        if (empty($dormitory->studentID) || empty($dormitory->roomNumber) || empty($dormitory->checkInDate)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $checkOutDate = !empty($dormitory->checkOutDate) ? "'{$dormitory->checkOutDate}'" : 'NULL';

        $query = "INSERT INTO Dormitories (StudentID, RoomNumber, CheckInDate, CheckOutDate, Notes) 
                  VALUES ('{$dormitory->studentID}', '{$dormitory->roomNumber}', '{$dormitory->checkInDate}', $checkOutDate, '{$dormitory->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при заселении: " . $this->conn->error);
        }

        return $result;
    }
}
?>