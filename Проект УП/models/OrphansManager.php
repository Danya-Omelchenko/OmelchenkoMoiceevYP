<?php
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
        $requiredFields = ['studentID', 'statusStart', 'statusEnd'];
        foreach ($requiredFields as $field) {
            if (empty($orphan->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO Orphans (StudentID, StatusOrder, StatusStart, StatusEnd, Notes)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issss",
            $orphan->studentID,
            $orphan->statusOrder,
            $orphan->statusStart,
            $orphan->statusEnd,
            $orphan->notes
        );

        return $stmt->execute();
    }

    public function editOrphanStatus(Orphan $orphan) {
        $requiredFields = ['studentID', 'statusStart', 'statusEnd'];
        foreach ($requiredFields as $field) {
            if (empty($orphan->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE Orphans SET
                  StudentID = ?,
                  StatusOrder = ?,
                  StatusStart = ?,
                  StatusEnd = ?,
                  Notes = ?
                  WHERE OrphanID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssi",
            $orphan->studentID,
            $orphan->statusOrder,
            $orphan->statusStart,
            $orphan->statusEnd,
            $orphan->notes,
            $orphan->orphanID
        );

        return $stmt->execute();
    }

    public function deleteOrphanStatus($orphanID) {
        $query = "DELETE FROM Orphans WHERE OrphanID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $orphanID);
        return $stmt->execute();
    }

    public function getOrphans($filters = []) {
        $query = "SELECT o.*, s.LastName, s.FirstName, s.MiddleName
                  FROM Orphans o
                  LEFT JOIN Students s ON o.StudentID = s.StudentID
                  WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($filters['studentID'])) {
            $query .= " AND o.StudentID = ?";
            $params[] = $filters['studentID'];
            $types .= 'i';
        }

        if (!empty($filters['statusOrder'])) {
            $query .= " AND o.StatusOrder LIKE ?";
            $params[] = '%'.$filters['statusOrder'].'%';
            $types .= 's';
        }

        if (!empty($filters['statusStart'])) {
            $query .= " AND o.StatusStart = ?";
            $params[] = $filters['statusStart'];
            $types .= 's';
        }

        if (!empty($filters['statusEnd'])) {
            $query .= " AND o.StatusEnd = ?";
            $params[] = $filters['statusEnd'];
            $types .= 's';
        }

        if (!empty($filters['notes'])) {
            $query .= " AND o.Notes LIKE ?";
            $params[] = '%'.$filters['notes'].'%';
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $orphans = [];
        while ($row = $result->fetch_assoc()) {
            $orphans[] = $row;
        }

        return $orphans;
    }
}
?>