<?
class RiskGroup {
    public $riskGroupID;
    public $studentID;
    public $type;
    public $registrationDate;
    public $registrationReason;
    public $removalDate;
    public $removalReason;
    public $notes;

    public function __construct($data) {
        $this->riskGroupID = $data['RiskGroupID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->type = $data['Type'] ?? '';
        $this->registrationDate = $data['RegistrationDate'] ?? '';
        $this->registrationReason = $data['RegistrationReason'] ?? '';
        $this->removalDate = $data['RemovalDate'] ?? '';
        $this->removalReason = $data['RemovalReason'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class RiskGroupManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addToRiskGroup(RiskGroup $riskGroup) {
        if (empty($riskGroup->studentID) || empty($riskGroup->type) || empty($riskGroup->registrationDate)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $removalDate = !empty($riskGroup->removalDate) ? "'{$riskGroup->removalDate}'" : 'NULL';
        $removalReason = !empty($riskGroup->removalReason) ? "'{$riskGroup->removalReason}'" : 'NULL';

        $query = "INSERT INTO RiskGroups (StudentID, Type, RegistrationDate, RegistrationReason, RemovalDate, RemovalReason, Notes) 
                  VALUES ('{$riskGroup->studentID}', '{$riskGroup->type}', '{$riskGroup->registrationDate}', '{$riskGroup->registrationReason}', $removalDate, $removalReason, '{$riskGroup->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при добавлении в группу риска: " . $this->conn->error);
        }

        return $result;
    }
}
?>