<?php
session_start();
include '../db_connection.php';
include '../models/DormitoriesManager.php';

$dormitoryManager = new DormitoryManager($conn);

// Обработка добавления студента в общежитие
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_dormitory'])) {
    try {
        $dormitory = new Dormitory($_POST);
        $result = $dormitoryManager->assignToDormitory($dormitory);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования студента в общежитии
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_dormitory'])) {
    try {
        $dormitory = new Dormitory($_POST);
        $result = $dormitoryManager->editInDormitory($dormitory);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка статусов с фильтрами
$filters = [
    'dormitoryID' => $_GET['dormitoryID'] ?? '',
    'studentID' => $_GET['studentID'] ?? '',
    'roomNumber' => $_GET['roomNumber'] ?? '',
    'checkInDate' => $_GET['checkInDate'] ?? '',
    'checkOutDate' => $_GET['checkOutDate'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

$statuses = $statusManager->getDormitory($filters);

// Возвращение данных в формате JSON
echo json_encode(['success' => true, 'data' => $statuses]);
?>
