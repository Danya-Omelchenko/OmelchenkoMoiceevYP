<?
class SocialScholarship {
    public $scholarshipID;
    public $studentID;
    public $documentBasis;
    public $paymentStart;
    public $paymentEnd;
    public $notes;

    public function __construct($data) {
        $this->scholarshipID = $data['ScholarshipID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->documentBasis = $data['DocumentBasis'] ?? '';
        $this->paymentStart = $data['PaymentStart'] ?? '';
        $this->paymentEnd = $data['PaymentEnd'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class SocialScholarshipManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function assignScholarship(SocialScholarship $scholarship) {
        if (empty($scholarship->studentID) || empty($scholarship->paymentStart) || empty($scholarship->paymentEnd)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $query = "INSERT INTO SocialScholarships (StudentID, DocumentBasis, PaymentStart, PaymentEnd, Notes) 
                  VALUES ('{$scholarship->studentID}', '{$scholarship->documentBasis}', '{$scholarship->paymentStart}', '{$scholarship->paymentEnd}', '{$scholarship->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при назначении стипендии: " . $this->conn->error);
        }

        return $result;
    }
}
?>