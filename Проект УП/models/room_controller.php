<?php
session_start();
include '../db_connection.php';
include '../models/RoomsManager.php';

$roomsManager = new RoomsManager($conn);

// Обработка добавления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    try {
        $room = new Room($_POST);
        $result = $roomsManager->addRoom($room);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_room'])) {
    try {
        $room = new Room($_POST);
        $result = $roomsManager->editRoom($room);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Обработка удаления
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    try {
        $result = $roomsManager->deleteRoom($_GET['delete_id']);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка с фильтрами
$filters = [
    'roomID' => $_GET['roomID'] ?? '',
    'roomNumber' => $_GET['roomNumber'] ?? '',
    'capacity' => $_GET['capacity'] ?? ''
];

try {
    $rooms = $roomsManager->getRooms($filters);
    echo json_encode(['success' => true, 'data' => $rooms]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit();
?>