<?php
session_start();
include '../db_connection.php';
include '../models/SpecialNeedsManager.php';

$specialNeedsManager = new SpecialNeedsManager($conn);

// Обработка добавления статуса ОВЗ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_special_needs'])) {
    try {
        $specialNeeds = new SpecialNeedsStudent($_POST);
        $result = $specialNeedsManager->addSpecialNeedsStatus($specialNeeds);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования статуса ОВЗ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_special_needs'])) {
    try {
        $specialNeeds = new SpecialNeedsStudent($_POST);
        $result = $specialNeedsManager->editSpecialNeedsStatus($specialNeeds);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}


// Получение списка статусов ОВЗ с фильтрами
$filters = [
    'studentID' => $_GET['studentID'] ?? '',
    'statusOrder' => $_GET['statusOrder'] ?? '',
    'statusStart' => $_GET['statusStart'] ?? '',
    'statusEnd' => $_GET['statusEnd'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

try {
    $specialNeedsList = $specialNeedsManager->getSpecialNeedsStatuses($filters);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $specialNeedsList]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit();
?>
