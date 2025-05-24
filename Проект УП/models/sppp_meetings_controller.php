<?php
session_start();
include '../db_connection.php';
include '../models/SpppMeetingsManager.php';

$spppMeetingsManager = new SpppMeetingsManager($conn);

// Обработка добавления заседания
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_meeting'])) {
    try {
        $meeting = new SpppMeeting($_POST);
        $result = $spppMeetingsManager->addMeeting($meeting);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

// Обработка редактирования заседания
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_meeting'])) {
    try {
        $meeting = new SpppMeeting($_POST);
        $result = $spppMeetingsManager->editMeeting($meeting);
        echo json_encode(['success' => $result]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}


// Получение списка заседаний с фильтрами
$filters = [
    'studentID' => $_GET['studentID'] ?? '',
    'meetingDate' => $_GET['meetingDate'] ?? '',
    'callReason' => $_GET['callReason'] ?? '',
    'presentEmployees' => $_GET['presentEmployees'] ?? '',
    'presentRepresentatives' => $_GET['presentRepresentatives'] ?? '',
    'callCause' => $_GET['callCause'] ?? '',
    'decision' => $_GET['decision'] ?? '',
    'notes' => $_GET['notes'] ?? ''
];

try {
    $meetings = $spppMeetingsManager->getMeetings($filters);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $meetings]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit();
?>
