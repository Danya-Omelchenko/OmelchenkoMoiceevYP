<?php
session_start();
include 'db_connection.php'; // Подключение к базе данных

// Инициализация переменных для фильтров
$search = isset($_GET['search']) ? $_GET['search'] : '';
$group = isset($_GET['group']) ? $_GET['group'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';
$fundingType = isset($_GET['fundingType']) ? $_GET['fundingType'] : '';
$admissionYear = isset($_GET['admissionYear']) ? $_GET['admissionYear'] : '';
$graduationYear = isset($_GET['graduationYear']) ? $_GET['graduationYear'] : '';
$educationLevel = isset($_GET['educationLevel']) ? $_GET['educationLevel'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';

// Создание SQL-запроса с учетом фильтров
$query = "SELECT * FROM Students WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (LastName LIKE '%$search%' OR FirstName LIKE '%$search%' OR MiddleName LIKE '%$search%')";
}

if (!empty($group)) {
    $query .= " AND GroupName = '$group'";
}

if (!empty($department)) {
    $query .= " AND Department = '$department'";
}

if (!empty($fundingType)) {
    $query .= " AND FundingType = '$fundingType'";
}

if (!empty($admissionYear)) {
    $query .= " AND AdmissionYear = '$admissionYear'";
}

if (!empty($graduationYear)) {
    $query .= " AND GraduationYear = '$graduationYear'";
}

if (!empty($educationLevel)) {
    $query .= " AND EducationLevel = '$educationLevel'";
}

if (!empty($gender)) {
    $query .= " AND Gender = '$gender'";
}

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
        <section>
            <h2>Список студентов</h2>
            <form method="get" action="students.php">
                <label for="search">Поиск по имени или фамилии:</label>
                <input type="text" id="search" name="search" placeholder="Введите имя или фамилию" value="<?php echo htmlspecialchars($search); ?>">

                <label for="group">Группа:</label>
                <input type="text" id="group" name="group" placeholder="Введите группу" value="<?php echo htmlspecialchars($group); ?>">

                <label for="department">Отделение:</label>
                <input type="text" id="department" name="department" placeholder="Введите отделение" value="<?php echo htmlspecialchars($department); ?>">

                <label for="fundingType">Финансирование:</label>
                <input type="text" id="fundingType" name="fundingType" placeholder="Введите тип финансирования" value="<?php echo htmlspecialchars($fundingType); ?>">

                <label for="admissionYear">Год поступления:</label>
                <input type="text" id="admissionYear" name="admissionYear" placeholder="Введите год поступления" value="<?php echo htmlspecialchars($admissionYear); ?>">

                <label for="graduationYear">Год окончания:</label>
                <input type="text" id="graduationYear" name="graduationYear" placeholder="Введите год окончания" value="<?php echo htmlspecialchars($graduationYear); ?>">

                <label for="educationLevel">Образование:</label>
                <input type="text" id="educationLevel" name="educationLevel" placeholder="Введите уровень образования" value="<?php echo htmlspecialchars($educationLevel); ?>">

                <label for="gender">Пол:</label>
                <input type="text" id="gender" name="gender" placeholder="Введите пол" value="<?php echo htmlspecialchars($gender); ?>">

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
        </section>
    </main>
    <footer>
        <p>&copy; Моисеев и Омельченко, 2025 Информационная система учебного заведения</p>
    </footer>
</body>
</html>
