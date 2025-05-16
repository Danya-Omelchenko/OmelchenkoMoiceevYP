<?php
session_start();
include 'db_connection.php'; // Подключение к базе данных

// Инициализация переменных для фильтров
$studentID = isset($_GET['studentID']) ? $_GET['studentID'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$registrationDate = isset($_GET['registrationDate']) ? $_GET['registrationDate'] : '';
$registrationReason = isset($_GET['registrationReason']) ? $_GET['registrationReason'] : '';
$removalDate = isset($_GET['removalDate']) ? $_GET['removalDate'] : '';
$removalReason = isset($_GET['removalReason']) ? $_GET['removalReason'] : '';
$notes = isset($_GET['notes']) ? $_GET['notes'] : '';

// Создание SQL-запроса с учетом фильтров
$query = "SELECT * FROM RiskGroups WHERE 1=1";

if (!empty($studentID)) {
    $query .= " AND StudentID = '$studentID'";
}

if (!empty($type)) {
    $query .= " AND Type LIKE '%$type%'";
}

if (!empty($registrationDate)) {
    $query .= " AND RegistrationDate = '$registrationDate'";
}

if (!empty($registrationReason)) {
    $query .= " AND RegistrationReason LIKE '%$registrationReason%'";
}

if (!empty($removalDate)) {
    $query .= " AND RemovalDate = '$removalDate'";
}

if (!empty($removalReason)) {
    $query .= " AND RemovalReason LIKE '%$removalReason%'";
}

if (!empty($notes)) {
    $query .= " AND Notes LIKE '%$notes%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Группа риска</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Группа риска</h1>
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
        <section>
            <h2>Список студентов группы риска</h2>
            <form method="get" action="risk_groups.php">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента" value="<?php echo htmlspecialchars($studentID); ?>">

                <label for="type">Тип:</label>
                <input type="text" id="type" name="type" placeholder="Введите тип" value="<?php echo htmlspecialchars($type); ?>">

                <label for="registrationDate">Дата постановки на учет:</label>
                <input type="date" id="registrationDate" name="registrationDate" value="<?php echo htmlspecialchars($registrationDate); ?>">

                <label for="registrationReason">Основание постановки на учет:</label>
                <input type="text" id="registrationReason" name="registrationReason" placeholder="Введите основание" value="<?php echo htmlspecialchars($registrationReason); ?>">

                <label for="removalDate">Дата снятия с учета:</label>
                <input type="date" id="removalDate" name="removalDate" value="<?php echo htmlspecialchars($removalDate); ?>">

                <label for="removalReason">Основание снятия с учета:</label>
                <input type="text" id="removalReason" name="removalReason" placeholder="Введите основание" value="<?php echo htmlspecialchars($removalReason); ?>">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание" value="<?php echo htmlspecialchars($notes); ?>">

                <button type="submit" class="button button-blue">Применить фильтры</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID студента</th>
                        <th>Тип</th>
                        <th>Дата постановки на учет</th>
                        <th>Основание постановки на учет</th>
                        <th>Дата снятия с учета</th>
                        <th>Основание снятия с учета</th>
                        <th>Примечание</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['StudentID']}</td>
                                <td>{$row['Type']}</td>
                                <td>{$row['RegistrationDate']}</td>
                                <td>{$row['RegistrationReason']}</td>
                                <td>{$row['RemovalDate']}</td>
                                <td>{$row['RemovalReason']}</td>
                                <td>{$row['Notes']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Нет данных о студентах группы риска</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; Моисеев и Омельченко, 2025 Информационная система учебного заведения</p>
    </footer>
</body>
</html>
