<?php
session_start();
include '../db_connection.php';
include '../models/Risk_groupsManager.php';

$riskGroupManager = new RiskGroupManager($conn);

// Обработка добавления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_risk_group'])) {
    try {
        $riskGroup = new RiskGroup($_POST);
        $result = $riskGroupManager->addToRiskGroup($riskGroup);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_risk_group'])) {
    try {
        $riskGroup = new RiskGroup($_POST);
        $result = $riskGroupManager->editRiskGroup($riskGroup);
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
        $result = $riskGroupManager->deleteRiskGroup($_GET['delete_id']);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка с фильтрами
$filters = [
    'studentID' => $_GET['studentID'] ?? '',
    'type' => $_GET['type'] ?? '',
    'registrationDate' => $_GET['registrationDate'] ?? '',
    'registrationReason' => $_GET['registrationReason'] ?? '',
    'removalDate' => $_GET['removalDate'] ?? '',
    'removalReason' => $_GET['removalReason'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

try {
    $riskGroups = $riskGroupManager->getRiskGroups($filters);
    echo json_encode(['success' => true, 'data' => $riskGroups]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit();
?>