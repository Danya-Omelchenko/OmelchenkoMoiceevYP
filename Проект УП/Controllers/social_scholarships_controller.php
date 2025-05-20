<?php
session_start();
include '../db_connection.php';
include '../models/Social_scholarshipsManager.php';

$socialScholarshipManager = new SocialScholarshipManager($conn);

// Обработка добавления статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_socialScholarship'])) {
    try {
        $socialScholarship = new SocialScholarship($_POST);
        $result = $socialScholarshipManager->assignScholarship($socialScholarship);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_socialScholarship'])) {
    try {
        $socialScholarship = new SocialScholarship($_POST);
        $result = $socialScholarshipManager->editToScholarship($socialScholarship);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Получение списка статусов с фильтрами
$filters = [
    'scholarshipID' => $_GET['scholarshipID'] ?? '',
    'studentID' => $_GET['studentID'] ?? '',
    'documentBasis' => $_GET['documentBasis'] ?? '',
    'paymentStart' => $_GET['paymentStart'] ?? '',
    'paymentEnd' => $_GET['paymentStart'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

$statuses = $statusManager->getSocialScholarship($filters);

// Возвращение данных в формате JSON
echo json_encode(['success' => true, 'data' => $statuses]);
?>
