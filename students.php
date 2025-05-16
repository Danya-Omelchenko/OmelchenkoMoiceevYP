<?php
session_start();
include 'db_connection.php'; // Подключение к базе данных

// Получение данных из базы данных
$query = "SELECT * FROM Students";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список студентов</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Список студентов</h1>
            </div>
            <div class="login-button">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="logout.php" class="button button-red">Выйти</a>
                <?php else: ?>
                    <a href="login.php" class="button button-red">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="students.php">Студенты</a></li>
            <li><a href="orphans.php">Сироты</a></li>
            <li><a href="disabled.php">Инвалиды</a></li>
            <li><a href="special_needs.php">ОВЗ</a></li>
            <li><a href="dormitories.php">Общежитие</a></li>
            <li><a href="risk_groups.php">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>

            <h2>Список студентов</h2>
            <form method="get" action="students.php">
                <label for="search">Поиск:</label>
                <input type="text" id="search" name="search" placeholder="Введите имя или фамилию">

                <label for="group">Группа:</label>
                <input type="text" id="group" name="group" placeholder="Введите группу">

                <label for="department">Отделение:</label>
                <input type="text" id="department" name="department" placeholder="Введите отделение">

                <button type="submit" class="button button-blue">Применить фильтры</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Дата рождения</th>
                        <th>Пол</th>
                        <th>Контактный номер</th>
                        <th>Образование</th>
                        <th>Отделение</th>
                        <th>Группа</th>
                        <th>Финансирование</th>
                        <th>Год поступления</th>
                        <th>Год окончания</th>
                        <th>Информация об отчислении</th>
                        <th>Дата отчисления</th>
                        <th>Примечание</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['LastName']}</td>
                                <td>{$row['FirstName']}</td>
                                <td>{$row['MiddleName']}</td>
                                <td>{$row['BirthDate']}</td>
                                <td>{$row['Gender']}</td>
                                <td>{$row['ContactNumber']}</td>
                                <td>{$row['EducationLevel']}</td>
                                <td>{$row['Department']}</td>
                                <td>{$row['GroupName']}</td>
                                <td>{$row['FundingType']}</td>
                                <td>{$row['AdmissionYear']}</td>
                                <td>{$row['GraduationYear']}</td>
                                <td>{$row['DismissalInfo']}</td>
                                <td>{$row['DismissalDate']}</td>
                                <td>{$row['Notes']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='14'>Нет данных о студентах</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

    </main>
    <footer>
        <p>&copy; Моисеев и Омельченко, 2025 Информационная система учебного заведения</p>
    </footer>
</body>
</html>
