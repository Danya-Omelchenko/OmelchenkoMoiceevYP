<?php
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
        $requiredFields = ['studentID', 'type', 'registrationDate'];
        foreach ($requiredFields as $field) {
            if (empty($riskGroup->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO RiskGroups (StudentID, Type, RegistrationDate, RegistrationReason, RemovalDate, RemovalReason, Notes)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssss",
            $riskGroup->studentID,
            $riskGroup->type,
            $riskGroup->registrationDate,
            $riskGroup->registrationReason,
            $riskGroup->removalDate,
            $riskGroup->removalReason,
            $riskGroup->notes
        );

        return $stmt->execute();
    }

    public function editRiskGroup(RiskGroup $riskGroup) {
        $requiredFields = ['studentID', 'type', 'registrationDate'];
        foreach ($requiredFields as $field) {
            if (empty($riskGroup->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE RiskGroups SET
                  StudentID = ?,
                  Type = ?,
                  RegistrationDate = ?,
                  RegistrationReason = ?,
                  RemovalDate = ?,
                  RemovalReason = ?,
                  Notes = ?
                  WHERE RiskGroupID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssssi",
            $riskGroup->studentID,
            $riskGroup->type,
            $riskGroup->registrationDate,
            $riskGroup->registrationReason,
            $riskGroup->removalDate,
            $riskGroup->removalReason,
            $riskGroup->notes,
            $riskGroup->riskGroupID
        );

        return $stmt->execute();
    }

    public function getRiskGroups($filters = []) {
        $query = "SELECT r.*, s.LastName, s.FirstName, s.MiddleName
                  FROM RiskGroups r
                  LEFT JOIN Students s ON r.StudentID = s.StudentID
                  WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($filters['studentID'])) {
            $query .= " AND r.StudentID = ?";
            $params[] = $filters['studentID'];
            $types .= 'i';
        }

        if (!empty($filters['type'])) {
            $query .= " AND r.Type LIKE ?";
            $params[] = '%'.$filters['type'].'%';
            $types .= 's';
        }

        if (!empty($filters['registrationDate'])) {
            $query .= " AND r.RegistrationDate = ?";
            $params[] = $filters['registrationDate'];
            $types .= 's';
        }

        if (!empty($filters['registrationReason'])) {
            $query .= " AND r.RegistrationReason LIKE ?";
            $params[] = '%'.$filters['registrationReason'].'%';
            $types .= 's';
        }

        if (!empty($filters['removalDate'])) {
            $query .= " AND r.RemovalDate = ?";
            $params[] = $filters['removalDate'];
            $types .= 's';
        }

        if (!empty($filters['removalReason'])) {
            $query .= " AND r.RemovalReason LIKE ?";
            $params[] = '%'.$filters['removalReason'].'%';
            $types .= 's';
        }

        if (!empty($filters['notes'])) {
            $query .= " AND r.Notes LIKE ?";
            $params[] = '%'.$filters['notes'].'%';
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $riskGroups = [];
        while ($row = $result->fetch_assoc()) {
            $riskGroups[] = $row;
        }

        return $riskGroups;
    }

    public function deleteRiskGroup($riskGroupID) {
        $query = "DELETE FROM RiskGroups WHERE RiskGroupID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $riskGroupID);
        return $stmt->execute();
    }
}
?>