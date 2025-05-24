<?php
session_start();
include '../db_connection.php';
include '../models/StudentManager.php';

$studentManager = new StudentManager($conn);

// Обработка добавления студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    try {
        $student = new Student($_POST);
        $result = $studentManager->addStudent($student);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_student'])) {
    try {
        $student = new Student($_POST);
        $result = $studentManager->editStudent($student);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка удаления студента
if (isset($_GET['delete_id'])) {
    try {
        $result = $studentManager->deleteStudent($_GET['delete_id']);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка студентов с фильтрами
$filters = [
    'search' => $_GET['search'] ?? '',
    'group' => $_GET['group'] ?? '',
    'department' => $_GET['department'] ?? '',
    'fundingType' => $_GET['fundingType'] ?? '',
    'admissionYear' => $_GET['admissionYear'] ?? '',
    'graduationYear' => $_GET['graduationYear'] ?? '',
    'educationLevel' => $_GET['educationLevel'] ?? '',
    'gender' => $_GET['gender'] ?? ''
];

$students = $studentManager->getStudents($filters);

// Возвращение данных в формате JSON
header('Content-Type: application/json');
echo json_encode(['success' => true, 'data' => $students]);
exit();
?>
