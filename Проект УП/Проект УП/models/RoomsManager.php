<?php
class Room {
    public $roomID;
    public $roomNumber;
    public $capacity;

    public function __construct($data) {
        $this->roomID = $data['RoomID'] ?? null;
        $this->roomNumber = $data['RoomNumber'] ?? '';
        $this->capacity = $data['Capacity'] ?? '';
    }
}

class RoomsManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addRoom(Room $room) {
        $requiredFields = ['roomNumber', 'capacity'];
        foreach ($requiredFields as $field) {
            if (!isset($room->$field) || $room->$field === '') {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "INSERT INTO Rooms (RoomNumber, Capacity) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $room->roomNumber, $room->capacity);

        return $stmt->execute();
    }

    public function editRoom(Room $room) {
        $requiredFields = ['roomNumber', 'capacity'];
        foreach ($requiredFields as $field) {
            if (!isset($room->$field) || $room->$field === '') {
                throw new Exception("Обязательное поле '$field' не заполнено");
            }
        }

        $query = "UPDATE Rooms SET RoomNumber = ?, Capacity = ? WHERE RoomID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $room->roomNumber, $room->capacity, $room->roomID);

        return $stmt->execute();
    }

    public function deleteRoom($roomID) {
        $query = "DELETE FROM Rooms WHERE RoomID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $roomID);

        return $stmt->execute();
    }

    public function getRooms($filters = []) {
        $query = "SELECT * FROM Rooms WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($filters['roomNumber'])) {
            $query .= " AND RoomNumber = ?";
            $params[] = $filters['roomNumber'];
            $types .= 'i';
        }

        if (!empty($filters['capacity'])) {
            $query .= " AND Capacity = ?";
            $params[] = $filters['capacity'];
            $types .= 'i';
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }

        return $rooms;
    }
}
?>