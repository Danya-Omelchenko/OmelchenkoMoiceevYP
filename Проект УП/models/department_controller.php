<?php
session_start();
include '../db_connection.php';
include '../models/DepartmentsManager.php';

$departmentsManager = new DepartmentsManager($conn);

// Обработка добавления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_department'])) {
    try {
        $department = new Department($_POST);
        $result = $departmentsManager->addDepartment($department);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_department'])) {
    try {
        $department = new Department($_POST);
        $result = $departmentsManager->editDepartment($department);
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
        $result = $departmentsManager->deleteDepartment($_GET['delete_id']);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка с фильтрами
$filters = [
    'departmentID' => $_GET['departmentID'] ?? '',
    'departmentName' => $_GET['departmentName'] ?? ''
];

try {
    $departments = $departmentsManager->getDepartments($filters);
    echo json_encode(['success' => true, 'data' => $departments]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit();
?>