<?php
session_start();
include '../db_connection.php';
include '../models/Risk_groupsManager.php';

$riskGroupManager = new RiskGroupManager($conn);

// Обработка добавления статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_statusRiskGroup'])) {
    try {
        $riskGroup = new RiskGroup($_POST);
        $result = $riskGroupManager->addToRiskGroup($riskGroup);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_statusRiskGroup'])) {
    try {
        $riskGroup = new RiskGroup($_POST);
        $result = $riskGroupManager->editToRiskGroup($riskGroup);
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
