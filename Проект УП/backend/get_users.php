<?php
    include_once("../db_connection.php");
    include_once("../models/StudentManager.php");

    header('Content-Type: application/json');

    $studentManager = new StudentManager($conn);
    $students = $studentManager->getStudents();
    echo json_encode($students);
    
?>
