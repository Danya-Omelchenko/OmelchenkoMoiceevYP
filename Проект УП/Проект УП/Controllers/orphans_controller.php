<?php
session_start();
include '../db_connection.php';
include '../models/OrphansManager.php';

$orphanManager = new OrphanManager($conn);

// Обработка добавления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_orphan'])) {
    try {
        $orphan = new Orphan($_POST);
        $result = $orphanManager->addOrphanStatus($orphan);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_orphan'])) {
    try {
        $orphan = new Orphan($_POST);
        $result = $orphanManager->editOrphanStatus($orphan);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}
// Получение списка с фильтрами
$filters = [
    'studentID' => $_GET['studentID'] ?? '',
    'statusOrder' => $_GET['statusOrder'] ?? '',
    'statusStart' => $_GET['statusStart'] ?? '',
    'statusEnd' => $_GET['statusEnd'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

try {
    $orphans = $orphanManager->getOrphans($filters);
    echo json_encode(['success' => true, 'data' => $orphans]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>