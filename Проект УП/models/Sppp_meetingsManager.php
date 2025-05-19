<?
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
        if (empty($meeting->studentID) || empty($meeting->meetingDate)) {
            throw new Exception("Обязательные поля не заполнены");
        }

        $query = "INSERT INTO SPPPMeetings (StudentID, MeetingDate, CallReason, PresentEmployees, PresentRepresentatives, CallCause, Decision, Notes) 
                  VALUES ('{$meeting->studentID}', '{$meeting->meetingDate}', '{$meeting->callReason}', '{$meeting->presentEmployees}', '{$meeting->presentRepresentatives}', '{$meeting->callCause}', '{$meeting->decision}', '{$meeting->notes}')";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Ошибка при добавлении заседания СППП: " . $this->conn->error);
        }

        return $result;
    }
}

?>