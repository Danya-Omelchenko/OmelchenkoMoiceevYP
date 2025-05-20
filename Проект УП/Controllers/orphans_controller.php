<?php
session_start();
include '../db_connection.php';
include '../models/OrphansManager.php';

$orphansManager = new OrphansManager($conn);

// Обработка добавления студента в общежитие
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_orphanStudent'])) {
    try {
        $orphan = new Orphan($_POST);
        $result = $orphansManager->addOrphanStatus($orphan);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования студента в общежитии
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_orphanStudent'])) {
    try {
        $orphan = new Orphan($_POST);
        $result = $orphansManager->editOrphanStatus($orphan);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка статусов с фильтрами
$filters = [
    'orphanID' => $_GET['orphanID'] ?? '',
    'studentID' => $_GET['studentID'] ?? '',
    'statusOrder' => $_GET['statusOrder'] ?? '',
    'statusStart' => $_GET['statusStart'] ?? '',
    'statusEnd' => $_GET['statusEnd'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

$statuses = $statusManager->getOrphan($filters);

// Возвращение данных в формате JSON
echo json_encode(['success' => true, 'data' => $statuses]);
?>
