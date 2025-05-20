<?php
session_start();
include '../db_connection.php';
include '../models/Svo_statusManager.php';

$statusManager = new SVOStatusManager($conn);

// Обработка добавления статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_status'])) {
    try {
        $status = new SVOStatus($_POST);
        $result = $statusManager->addSVOStatus($status);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_status'])) {
    try {
        $status = new SVOStatus($_POST);
        $result = $statusManager->editSVOStatus($status);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка статусов с фильтрами
$filters = [
    'studentID' => $_GET['studentID'] ?? '',
    'documentBasis' => $_GET['documentBasis'] ?? '',
    'statusStart' => $_GET['statusStart'] ?? '',
    'statusEnd' => $_GET['statusEnd'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

$statuses = $statusManager->getSVOStatuses($filters);

// Возвращение данных в формате JSON
echo json_encode(['success' => true, 'data' => $statuses]);
?>
