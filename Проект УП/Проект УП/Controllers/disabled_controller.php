<?php
session_start();
include '../db_connection.php';
include '../models/DisabledManager.php';

$disabledStudentManager = new DisabledStudentManager($conn);

// Обработка добавления статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_disabledStudent'])) {
    try {
        $disabledStudent = new DisabledStudent($_POST);
        $result = $disabledStudentManager->addDisabledStudent($disabledStudent);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_disabledStudent'])) {
    try {
        $disabledStudent = new DisabledStudent($_POST);
        $result = $disabledStudentManager->editDisabledStudent($disabledStudent);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка для фильтров
$filters = [
    'disabledStudentID' => $_GET['disabledStudentID'] ?? '',
    'studentID' => $_GET['studentID'] ?? '',
    'statusOrder' => $_GET['statusOrder'] ?? '',
    'statusStart' => $_GET['statusStart'] ?? '',
    'statusEnd' => $_GET['statusEnd'] ?? '',
    'disabilityType' => $_GET['disabilityType'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

$statuses = $disabledStudentManager->getDisabledStudents($filters);

// Возвращение данных в формате JSON
echo json_encode(['success' => true, 'data' => $statuses]);
?>
