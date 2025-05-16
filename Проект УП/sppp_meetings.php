<?php
session_start();
include 'db_connection.php'; // Подключение к базе данных

// Инициализация переменных для фильтров
$studentID = isset($_GET['studentID']) ? $_GET['studentID'] : '';
$meetingDate = isset($_GET['meetingDate']) ? $_GET['meetingDate'] : '';
$callReason = isset($_GET['callReason']) ? $_GET['callReason'] : '';
$presentEmployees = isset($_GET['presentEmployees']) ? $_GET['presentEmployees'] : '';
$presentRepresentatives = isset($_GET['presentRepresentatives']) ? $_GET['presentRepresentatives'] : '';
$callCause = isset($_GET['callCause']) ? $_GET['callCause'] : '';
$decision = isset($_GET['decision']) ? $_GET['decision'] : '';
$notes = isset($_GET['notes']) ? $_GET['notes'] : '';

// Создание SQL-запроса с учетом фильтров
$query = "SELECT * FROM SPPPMeetings WHERE 1=1";

if (!empty($studentID)) {
    $query .= " AND StudentID = '$studentID'";
}

if (!empty($meetingDate)) {
    $query .= " AND MeetingDate = '$meetingDate'";
}

if (!empty($callReason)) {
    $query .= " AND CallReason LIKE '%$callReason%'";
}

if (!empty($presentEmployees)) {
    $query .= " AND PresentEmployees LIKE '%$presentEmployees%'";
}

if (!empty($presentRepresentatives)) {
    $query .= " AND PresentRepresentatives LIKE '%$presentRepresentatives%'";
}

if (!empty($callCause)) {
    $query .= " AND CallCause LIKE '%$callCause%'";
}

if (!empty($decision)) {
    $query .= " AND Decision LIKE '%$decision%'";
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
    <title>СППП</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>СППП</h1>
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
            <h2>Список заседаний СППП</h2>
            <form method="get" action="sppp_meetings.php">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента" value="<?php echo htmlspecialchars($studentID); ?>">

                <label for="meetingDate">Дата заседания:</label>
                <input type="date" id="meetingDate" name="meetingDate" value="<?php echo htmlspecialchars($meetingDate); ?>">

                <label for="callReason">Основание вызова:</label>
                <input type="text" id="callReason" name="callReason" placeholder="Введите основание" value="<?php echo htmlspecialchars($callReason); ?>">

                <label for="presentEmployees">Присутствовали сотрудники:</label>
                <input type="text" id="presentEmployees" name="presentEmployees" placeholder="Введите сотрудников" value="<?php echo htmlspecialchars($presentEmployees); ?>">

                <label for="presentRepresentatives">Присутствовали представители:</label>
                <input type="text" id="presentRepresentatives" name="presentRepresentatives" placeholder="Введите представителей" value="<?php echo htmlspecialchars($presentRepresentatives); ?>">

                <label for="callCause">Причина вызова:</label>
                <input type="text" id="callCause" name="callCause" placeholder="Введите причину" value="<?php echo htmlspecialchars($callCause); ?>">

                <label for="decision">Решение:</label>
                <input type="text" id="decision" name="decision" placeholder="Введите решение" value="<?php echo htmlspecialchars($decision); ?>">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание" value="<?php echo htmlspecialchars($notes); ?>">

                <button type="submit" class="button button-blue">Применить фильтры</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID студента</th>
                        <th>Дата заседания</th>
                        <th>Основание вызова</th>
                        <th>Присутствовали сотрудники</th>
                        <th>Присутствовали представители</th>
                        <th>Причина вызова</th>
                        <th>Решение</th>
                        <th>Примечание</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['StudentID']}</td>
                                <td>{$row['MeetingDate']}</td>
                                <td>{$row['CallReason']}</td>
                                <td>{$row['PresentEmployees']}</td>
                                <td>{$row['PresentRepresentatives']}</td>
                                <td>{$row['CallCause']}</td>
                                <td>{$row['Decision']}</td>
                                <td>{$row['Notes']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Нет данных о заседаниях СППП</td></tr>";
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
