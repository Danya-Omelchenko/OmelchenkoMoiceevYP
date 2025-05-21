<?php
session_start();
include '../db_connection.php';
include '../models/Social_scholarshipsManager.php';

$scholarshipManager = new SocialScholarshipManager($conn);

// Обработка добавления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_scholarship'])) {
    try {
        $scholarship = new SocialScholarship($_POST);
        $result = $scholarshipManager->assignScholarship($scholarship);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_scholarship'])) {
    try {
        $scholarship = new SocialScholarship($_POST);
        $result = $scholarshipManager->editScholarship($scholarship);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка удаления
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    try {
        $result = $scholarshipManager->deleteScholarship($_GET['delete_id']);
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
    'documentBasis' => $_GET['documentBasis'] ?? '',
    'paymentStart' => $_GET['paymentStart'] ?? '',
    'paymentEnd' => $_GET['paymentEnd'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

try {
    $scholarships = $scholarshipManager->getScholarships($filters);
    echo json_encode(['success' => true, 'data' => $scholarships]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>