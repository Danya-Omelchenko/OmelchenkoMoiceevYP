<?php
// Проверка авторизации пользователя
session_start();

// Если пользователь не авторизован, перенаправляем на страницу входа
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    // Сохраняем URL, на который пытался зайти пользователь
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    // Перенаправляем на страницу входа
    header('Location: login.php');
    exit;
}

// Проверка разрешений для роли, если нужно
function checkAdminPermission() {
    if ($_SESSION['role'] !== 'admin') {
        die("У вас нет прав для выполнения данного действия");
    }
    return true;
}
?>