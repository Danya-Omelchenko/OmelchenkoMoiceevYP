<?php
class SPPPMeeting {
    public $meetingID;
    public $studentID;
    public $meetingDate;
    public $callReason;
    public $presentEmployees;
    public $presentRepresentatives;
    public $callCause;
    public $decision;
    public $notes;

    public function __construct($data) {
        $this->meetingID = $data['MeetingID'] ?? null;
        $this->studentID = $data['StudentID'] ?? null;
        $this->meetingDate = $data['MeetingDate'] ?? '';
        $this->callReason = $data['CallReason'] ?? '';
        $this->presentEmployees = $data['PresentEmployees'] ?? '';
        $this->presentRepresentatives = $data['PresentRepresentatives'] ?? '';
        $this->callCause = $data['CallCause'] ?? '';
        $this->decision = $data['Decision'] ?? '';
        $this->notes = $data['Notes'] ?? '';
    }
}

class SPPPManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addMeeting(SPPPMeeting $meeting) {
        $requiredFields = ['studentID', 'meetingDate', 'callReason'];
        foreach ($requiredFields as $field) {
            if (empty($meeting->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO SPPPMeetings (
                    StudentID, MeetingDate, CallReason, 
                    PresentEmployees, PresentRepresentatives, 
                    CallCause, Decision, Notes
                  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssssss",
            $meeting->studentID,
            $meeting->meetingDate,
            $meeting->callReason,
            $meeting->presentEmployees,
            $meeting->presentRepresentatives,
            $meeting->callCause,
            $meeting->decision,
            $meeting->notes
        );
        
        return $stmt->execute();
    }

    public function editMeeting(SPPPMeeting $meeting) {
        $requiredFields = ['studentID', 'meetingDate', 'callReason'];
        foreach ($requiredFields as $field) {
            if (empty($meeting->$field)) {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE SPPPMeetings SET
                    StudentID = ?,
                    MeetingDate = ?,
                    CallReason = ?,
                    PresentEmployees = ?,
                    PresentRepresentatives = ?,
                    CallCause = ?,
                    Decision = ?,
                    Notes = ?
                  WHERE MeetingID = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssssssi",
            $meeting->studentID,
            $meeting->meetingDate,
            $meeting->callReason,
            $meeting->presentEmployees,
            $meeting->presentRepresentatives,
            $meeting->callCause,
            $meeting->decision,
            $meeting->notes,
            $meeting->meetingID
        );
        
        return $stmt->execute();
    }

    public function getMeetings($filters = []) {
        $query = "SELECT m.*, s.LastName, s.FirstName, s.MiddleName 
                  FROM SPPPMeetings m
                  LEFT JOIN Students s ON m.StudentID = s.StudentID
                  WHERE 1=1";
        
        $params = [];
        $types = '';
        
        if (!empty($filters['studentID'])) {
            $query .= " AND m.StudentID = ?";
            $params[] = $filters['studentID'];
            $types .= 'i';
        }
        
        if (!empty($filters['meetingDate'])) {
            $query .= " AND m.MeetingDate = ?";
            $params[] = $filters['meetingDate'];
            $types .= 's';
        }
        
        if (!empty($filters['callReason'])) {
            $query .= " AND m.CallReason LIKE ?";
            $params[] = '%'.$filters['callReason'].'%';
            $types .= 's';
        }
        
        if (!empty($filters['presentEmployees'])) {
            $query .= " AND m.PresentEmployees LIKE ?";
            $params[] = '%'.$filters['presentEmployees'].'%';
            $types .= 's';
        }

        if (!empty($filters['presentRepresentatives'])) {
            $query .= " AND m.PresentRepresentatives LIKE ?";
            $params[] = '%'.$filters['presentRepresentatives'].'%';
            $types .= 's';
        }

        if (!empty($filters['callCause'])) {
            $query .= " AND m.CallCause LIKE ?";
            $params[] = '%'.$filters['callCause'].'%';
            $types .= 's';
        }

        if (!empty($filters['decision'])) {
            $query .= " AND m.Decision LIKE ?";
            $params[] = '%'.$filters['decision'].'%';
            $types .= 's';
        }

        if (!empty($filters['notes'])) {
            $query .= " AND m.Notes LIKE ?";
            $params[] = '%'.$filters['notes'].'%';
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $meetings = [];
        while ($row = $result->fetch_assoc()) {
            $meetings[] = $row;
        }
        
        return $meetings;
    }
}
?>