<?php
class Student {
    public $studentID;
    public $lastName;
    public $firstName;
    public $middleName;
    public $birthDate;
    public $gender;
    public $contactNumber;
    public $educationLevel;
    public $department;
    public $groupName;
    public $fundingType;
    public $admissionYear;
    public $graduationYear;
    public $dismissalInfo;
    public $dismissalDate;
    public $notes;
    public $parentsInfo;
    public $penalties;

    public function __construct($data) {
        $this->studentID = $data['StudentID'] ?? null;
        $this->lastName = $data['LastName'] ?? '';
        $this->firstName = $data['FirstName'] ?? '';
        $this->middleName = $data['MiddleName'] ?? '';
        $this->birthDate = $data['BirthDate'] ?? '';
        $this->gender = $data['Gender'] ?? '';
        $this->contactNumber = $data['ContactNumber'] ?? '';
        $this->educationLevel = $data['EducationLevel'] ?? '';
        $this->department = $data['Department'] ?? '';
        $this->groupName = $data['GroupName'] ?? '';
        $this->fundingType = $data['FundingType'] ?? '';
        $this->admissionYear = $data['AdmissionYear'] ?? '';
        $this->graduationYear = $data['GraduationYear'] ?? '';
        $this->dismissalInfo = $data['DismissalInfo'] ?? '';
        $this->dismissalDate = $data['DismissalDate'] ?? '';
        $this->notes = $data['Notes'] ?? '';
        $this->parentsInfo = $data['ParentsInfo'] ?? '';
        $this->penalties = $data['Penalties'] ?? '';
    }
}

class StudentManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addStudent(Student $student) {
        $requiredFields = [
            'lastName', 'firstName', 'birthDate',
            'gender', 'contactNumber', 'educationLevel',
            'department', 'groupName', 'fundingType', 'admissionYear'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($student->$field) || $student->$field === '') {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $birthDate = !empty($student->birthDate) ? "'{$student->birthDate}'" : 'NULL';
        $dismissalDate = !empty($student->dismissalDate) ? "'{$student->dismissalDate}'" : 'NULL';
        $dismissalInfo = !empty($student->dismissalInfo) ? "'{$student->dismissalInfo}'" : 'NULL';
        $penalties = !empty($student->penalties) ? "'{$student->penalties}'" : 'NULL';

        $query = "INSERT INTO Students (LastName, FirstName, MiddleName, BirthDate, Gender, ContactNumber, EducationLevel, Department, GroupName, FundingType, AdmissionYear, GraduationYear, DismissalInfo, DismissalDate, Notes, ParentsInfo, Penalties)
                  VALUES ('{$student->lastName}', '{$student->firstName}', '{$student->middleName}', $birthDate, '{$student->gender}', '{$student->contactNumber}', '{$student->educationLevel}', '{$student->department}', '{$student->groupName}', '{$student->fundingType}', '{$student->admissionYear}', '{$student->graduationYear}', $dismissalInfo, $dismissalDate, '{$student->notes}', '{$student->parentsInfo}', $penalties)";

        return $this->conn->query($query);
    }

    public function editStudent(Student $student) {
        $requiredFields = [
            'lastName', 'firstName', 'birthDate',
            'gender', 'contactNumber', 'educationLevel',
            'department', 'groupName', 'fundingType', 'admissionYear'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($student->$field) || $student->$field === '') {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $birthDate = !empty($student->birthDate) ? "'{$student->birthDate}'" : 'NULL';
        $dismissalDate = !empty($student->dismissalDate) ? "'{$student->dismissalDate}'" : 'NULL';
        $dismissalInfo = !empty($student->dismissalInfo) ? "'{$student->dismissalInfo}'" : 'NULL';
        $penalties = !empty($student->penalties) ? "'{$student->penalties}'" : 'NULL';

        $query = "UPDATE Students SET
                  LastName = '{$student->lastName}',
                  FirstName = '{$student->firstName}',
                  MiddleName = '{$student->middleName}',
                  BirthDate = $birthDate,
                  Gender = '{$student->gender}',
                  ContactNumber = '{$student->contactNumber}',
                  EducationLevel = '{$student->educationLevel}',
                  Department = '{$student->department}',
                  GroupName = '{$student->groupName}',
                  FundingType = '{$student->fundingType}',
                  AdmissionYear = '{$student->admissionYear}',
                  GraduationYear = '{$student->graduationYear}',
                  DismissalInfo = $dismissalInfo,
                  DismissalDate = $dismissalDate,
                  Notes = '{$student->notes}',
                  ParentsInfo = '{$student->parentsInfo}',
                  Penalties = $penalties
                  WHERE StudentID = '{$student->studentID}'";

        return $this->conn->query($query);
    }

    public function deleteStudent($studentID) {
        $this->conn->begin_transaction();

        try {
            $tables = ['DisabledStudents', 'Orphans', 'SpecialNeedsStudents', 'Dormitories', 'RiskGroups', 'SPPPMeetings', 'SVOStatus', 'SocialScholarships', 'Files'];

            foreach ($tables as $table) {
                $query = "DELETE FROM $table WHERE StudentID = '$studentID'";
                $this->conn->query($query);
            }

            $query = "DELETE FROM Students WHERE StudentID = '$studentID'";
            $this->conn->query($query);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function getStudents($filters = []) {
    $query = "SELECT * FROM Students WHERE 1=1";
    $params = [];
    $types = '';

    if (!empty($filters['search'])) {
        $query .= " AND (LastName LIKE ? OR FirstName LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }

    // Добавьте другие фильтры аналогично...

    $stmt = $this->conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row; // Возвращаем ассоциативный массив вместо объекта
    }

    return $students;
}
}
?>
