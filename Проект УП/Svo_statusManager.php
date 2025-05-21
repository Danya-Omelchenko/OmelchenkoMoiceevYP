<?php
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
        $requiredFields = ['studentID', 'documentBasis', 'statusStart', 'statusEnd'];
        foreach ($requiredFields as $field) {
            if (empty($status->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO SVOStatus (StudentID, DocumentBasis, StatusStart, StatusEnd, Notes)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issss",
            $status->studentID,
            $status->documentBasis,
            $status->statusStart,
            $status->statusEnd,
            $status->notes
        );

        return $stmt->execute();
    }

    public function editSVOStatus(SVOStatus $status) {
        $requiredFields = ['studentID', 'documentBasis', 'statusStart', 'statusEnd'];
        foreach ($requiredFields as $field) {
            if (empty($status->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE SVOStatus SET
                  StudentID = ?,
                  DocumentBasis = ?,
                  StatusStart = ?,
                  StatusEnd = ?,
                  Notes = ?
                  WHERE SVOStatusID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssi",
            $status->studentID,
            $status->documentBasis,
            $status->statusStart,
            $status->statusEnd,
            $status->notes,
            $status->svoStatusID
        );

        return $stmt->execute();
    }

    public function getSVOStatuses($filters = []) {
        $query = "SELECT s.*, st.LastName, st.FirstName, st.MiddleName
                  FROM SVOStatus s
                  LEFT JOIN Students st ON s.StudentID = st.StudentID
                  WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($filters['studentID'])) {
            $query .= " AND s.StudentID = ?";
            $params[] = $filters['studentID'];
            $types .= 'i';
        }

        if (!empty($filters['documentBasis'])) {
            $query .= " AND s.DocumentBasis LIKE ?";
            $params[] = '%'.$filters['documentBasis'].'%';
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

        $statuses = [];
        while ($row = $result->fetch_assoc()) {
            $statuses[] = $row;
        }

        return $statuses;
    }
}
?>
