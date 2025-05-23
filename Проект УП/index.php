<?php
require_once "auth_check.php";


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система управления данными студентов</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Система управления данными студентов</h1>
            </div>
            <div class="login-button">
                <?php
require_once "auth_check.php";
 if (isset($_SESSION['username'])): ?>
                    <a href="logout.php" class="button button-red">Выйти</a>
                <?php
require_once "auth_check.php";
 else: ?>
                    <a href="login.php" class="button button-red">Войти</a>
                <?php
require_once "auth_check.php";
 endif; ?>
            </div>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="students.php">Студенты</a></li>
            <li><a href="departments.php">Отделения</a></li>
            <li><a href="orphans.php">Сироты</a></li>
            <li><a href="disabled.php">Инвалиды</a></li>
            <li><a href="special_needs.php">ОВЗ</a></li>
            <li><a href="dormitories.php">Общежитие</a></li>
            <li><a href="rooms.php">Комнаты</a></li>
            <li><a href="risk_groups.php">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section class="main_inform">
            <article>
                <h2>Добро пожаловать в информационную систему учебного заведения</h2>
                <p>Здесь вы можете управлять данными о студентах, сиротах, инвалидах, студентах с ОВЗ, проживающих в общежитии, группах риска, заседаниях СППП, статусе СВО и социальных стипендиях.</p>
            </article>
        <article class="sectionFlex">
            <div>
                <h2>Руководство пользователя </h2>
                <p>Система управления данными студентов предназначена для автоматизации и упрощения управления информацией о студентах в учебном заведении. Она позволяет администраторам и сотрудников эффективно взаимодействовать с системой для выполнения различных задач, таких как управление данными студентов, отслеживание успеваемости, управление общежитиями и многое другое.</p>
            </div>
            <div>
                <h2>Подготовка к работе</h2>
                <p>Вход в систему <br>
                1. Нажмите кнопку “Войти” на главной странице. <br>
                2. Введите имя пользователя, пароль и роль в окне входа. <br>
                3. Нажмите кнопку "Войти" для доступа к системе.</p>
            </div>
        </article>
        <article>
            <h2>Аварийные ситуации</h2>
            <p>В случае возникновения аварийных ситуаций, таких как сбои системы или потери данных, обратитесь к администратору системы для получения помощи.</p>
        </article>
        </section>
    </main>
    <footer>
        <div class="logo-title">
            <img src="img/logo.png" alt="Логотип">
            <p>&copy; Моисеев и Омельченко, 2025 Система управления данными студентов</p>
        </div>
        
    </footer>
</body>
</html>
