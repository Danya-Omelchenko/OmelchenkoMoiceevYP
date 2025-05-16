<?php
session_start();
include 'db_connection.php'; // Подключение к базе данных

// Инициализация переменных для фильтров
$studentID = isset($_GET['studentID']) ? $_GET['studentID'] : '';
$documentBasis = isset($_GET['documentBasis']) ? $_GET['documentBasis'] : '';
$paymentStart = isset($_GET['paymentStart']) ? $_GET['paymentStart'] : '';
$paymentEnd = isset($_GET['paymentEnd']) ? $_GET['paymentEnd'] : '';
$notes = isset($_GET['notes']) ? $_GET['notes'] : '';

// Создание SQL-запроса с учетом фильтров
$query = "SELECT * FROM SocialScholarships WHERE 1=1";

if (!empty($studentID)) {
    $query .= " AND StudentID = '$studentID'";
}

if (!empty($documentBasis)) {
    $query .= " AND DocumentBasis LIKE '%$documentBasis%'";
}

if (!empty($paymentStart)) {
    $query .= " AND PaymentStart = '$paymentStart'";
}

if (!empty($paymentEnd)) {
    $query .= " AND PaymentEnd = '$paymentEnd'";
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
    <title>Социальная стипендия</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Социальная стипендия</h1>
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
            <h2>Список студентов с социальной стипендией</h2>
            <form method="get" action="social_scholarships.php">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента" value="<?php echo htmlspecialchars($studentID); ?>">

                <label for="documentBasis">Документ основание:</label>
                <input type="text" id="documentBasis" name="documentBasis" placeholder="Введите документ" value="<?php echo htmlspecialchars($documentBasis); ?>">

                <label for="paymentStart">Начало выплаты:</label>
                <input type="date" id="paymentStart" name="paymentStart" value="<?php echo htmlspecialchars($paymentStart); ?>">

                <label for="paymentEnd">Конец выплаты:</label>
                <input type="date" id="paymentEnd" name="paymentEnd" value="<?php echo htmlspecialchars($paymentEnd); ?>">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание" value="<?php echo htmlspecialchars($notes); ?>">

                <button type="submit" class="button button-blue">Применить фильтры</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID студента</th>
                        <th>Документ основание</th>
                        <th>Начало выплаты</th>
                        <th>Конец выплаты</th>
                        <th>Примечание</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['StudentID']}</td>
                                <td>{$row['DocumentBasis']}</td>
                                <td>{$row['PaymentStart']}</td>
                                <td>{$row['PaymentEnd']}</td>
                                <td>{$row['Notes']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Нет данных о студентах с социальной стипендией</td></tr>";
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
