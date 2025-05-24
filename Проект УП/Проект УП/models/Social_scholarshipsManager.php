<?php
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
        $requiredFields = ['studentID', 'paymentStart'];
        foreach ($requiredFields as $field) {
            if (empty($scholarship->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO SocialScholarships (StudentID, DocumentBasis, PaymentStart, PaymentEnd, Notes)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issss",
            $scholarship->studentID,
            $scholarship->documentBasis,
            $scholarship->paymentStart,
            $scholarship->paymentEnd,
            $scholarship->notes
        );

        return $stmt->execute();
    }

    public function editScholarship(SocialScholarship $scholarship) {
        $requiredFields = ['studentID', 'paymentStart'];
        foreach ($requiredFields as $field) {
            if (empty($scholarship->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE SocialScholarships SET
                  StudentID = ?,
                  DocumentBasis = ?,
                  PaymentStart = ?,
                  PaymentEnd = ?,
                  Notes = ?
                  WHERE ScholarshipID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssi",
            $scholarship->studentID,
            $scholarship->documentBasis,
            $scholarship->paymentStart,
            $scholarship->paymentEnd,
            $scholarship->notes,
            $scholarship->scholarshipID
        );

        return $stmt->execute();
    }

    public function deleteScholarship($scholarshipID) {
        $query = "DELETE FROM SocialScholarships WHERE ScholarshipID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $scholarshipID);
        return $stmt->execute();
    }

    public function getScholarships($filters = []) {
        $query = "SELECT ss.*, s.LastName, s.FirstName, s.MiddleName
                  FROM SocialScholarships ss
                  LEFT JOIN Students s ON ss.StudentID = s.StudentID
                  WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($filters['studentID'])) {
            $query .= " AND ss.StudentID = ?";
            $params[] = $filters['studentID'];
            $types .= 'i';
        }

        if (!empty($filters['documentBasis'])) {
            $query .= " AND ss.DocumentBasis LIKE ?";
            $params[] = '%'.$filters['documentBasis'].'%';
            $types .= 's';
        }

        if (!empty($filters['paymentStart'])) {
            $query .= " AND ss.PaymentStart = ?";
            $params[] = $filters['paymentStart'];
            $types .= 's';
        }

        if (!empty($filters['paymentEnd'])) {
            $query .= " AND ss.PaymentEnd = ?";
            $params[] = $filters['paymentEnd'];
            $types .= 's';
        }

        if (!empty($filters['notes'])) {
            $query .= " AND ss.Notes LIKE ?";
            $params[] = '%'.$filters['notes'].'%';
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $scholarships = [];
        while ($row = $result->fetch_assoc()) {
            $scholarships[] = $row;
        }

        return $scholarships;
    }
}
?>