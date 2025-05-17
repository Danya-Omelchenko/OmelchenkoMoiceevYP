<?php
session_start();
include 'db_connection.php'; // Подключение к базе данных

// Обработка добавления студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $birthDate = $_POST['birthDate'];
    $gender = $_POST['gender'];
    $contactNumber = $_POST['contactNumber'];
    $educationLevel = $_POST['educationLevel'];
    $department = $_POST['department'];
    $groupName = $_POST['groupName'];
    $fundingType = $_POST['fundingType'];
    $admissionYear = $_POST['admissionYear'];
    $graduationYear = $_POST['graduationYear'];
    $dismissalInfo = !empty($_POST['dismissalInfo']) ? "'{$_POST['dismissalInfo']}'" : 'NULL';
    $dismissalDate = !empty($_POST['dismissalDate']) ? "'{$_POST['dismissalDate']}'" : 'NULL';
    $notes = $_POST['notes'];

    $query = "INSERT INTO Students (LastName, FirstName, MiddleName, BirthDate, Gender, ContactNumber, EducationLevel, Department, GroupName, FundingType, AdmissionYear, GraduationYear, DismissalInfo, DismissalDate, Notes)
              VALUES ('$lastName', '$firstName', '$middleName', '$birthDate', '$gender', '$contactNumber', '$educationLevel', '$department', '$groupName', '$fundingType', '$admissionYear', '$graduationYear', $dismissalInfo, $dismissalDate, '$notes')";
    $conn->query($query);
}

// Обработка редактирования студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_student'])) {
    $studentID = $_POST['studentID'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $birthDate = $_POST['birthDate'];
    $gender = $_POST['gender'];
    $contactNumber = $_POST['contactNumber'];
    $educationLevel = $_POST['educationLevel'];
    $department = $_POST['department'];
    $groupName = $_POST['groupName'];
    $fundingType = $_POST['fundingType'];
    $admissionYear = $_POST['admissionYear'];
    $graduationYear = $_POST['graduationYear'];
    $dismissalInfo = !empty($_POST['dismissalInfo']) ? "'{$_POST['dismissalInfo']}'" : 'NULL';
    $dismissalDate = !empty($_POST['dismissalDate']) ? "'{$_POST['dismissalDate']}'" : 'NULL';
    $notes = $_POST['notes'];

    $query = "UPDATE Students SET
              LastName = '$lastName',
              FirstName = '$firstName',
              MiddleName = '$middleName',
              BirthDate = '$birthDate',
              Gender = '$gender',
              ContactNumber = '$contactNumber',
              EducationLevel = '$educationLevel',
              Department = '$department',
              GroupName = '$groupName',
              FundingType = '$fundingType',
              AdmissionYear = '$admissionYear',
              GraduationYear = '$graduationYear',
              DismissalInfo = $dismissalInfo,
              DismissalDate = $dismissalDate,
              Notes = '$notes'
              WHERE StudentID = '$studentID'";
    $conn->query($query);
}

// Обработка удаления студента
if (isset($_GET['delete_id'])) {
    $studentID = $_GET['delete_id'];
    $query = "DELETE FROM Students WHERE StudentID = '$studentID'";
    $conn->query($query);
}

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
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить студента</button>
            <?php endif; ?>
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

            <!-- Форма для добавления студента -->
            <div id="addForm" style="display: none;">
                <h3>Добавить студента</h3>
                <form method="post" action="students.php">
                    <input type="hidden" name="add_student" value="1">
                    <label for="lastName">Фамилия:</label>
                    <input type="text" id="lastName" name="lastName" required>

                    <label for="firstName">Имя:</label>
                    <input type="text" id="firstName" name="firstName" required>

                    <label for="middleName">Отчество:</label>
                    <input type="text" id="middleName" name="middleName" required>

                    <label for="birthDate">Дата рождения:</label>
                    <input type="date" id="birthDate" name="birthDate" required>

                    <label for="gender">Пол:</label>
                    <input type="text" id="gender" name="gender" required>

                    <label for="contactNumber">Контактный номер:</label>
                    <input type="text" id="contactNumber" name="contactNumber" required>

                    <label for="educationLevel">Образование:</label>
                    <input type="text" id="educationLevel" name="educationLevel" required>

                    <label for="department">Отделение:</label>
                    <input type="text" id="department" name="department" required>

                    <label for="groupName">Группа:</label>
                    <input type="text" id="groupName" name="groupName" required>

                    <label for="fundingType">Финансирование:</label>
                    <input type="text" id="fundingType" name="fundingType" required>

                    <label for="admissionYear">Год поступления:</label>
                    <input type="text" id="admissionYear" name="admissionYear" required>

                    <label for="graduationYear">Год окончания:</label>
                    <input type="text" id="graduationYear" name="graduationYear" required>

                    <label for="dismissalInfo">Информация об отчислении:</label>
                    <input type="text" id="dismissalInfo" name="dismissalInfo">

                    <label for="dismissalDate">Дата отчисления:</label>
                    <input type="date" id="dismissalDate" name="dismissalDate">

                    <label for="notes">Примечание:</label>
                    <input type="text" id="notes" name="notes">

                    <button type="submit" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма для редактирования студента -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать студента</h3>
                <form method="post" action="students.php">
                    <input type="hidden" name="edit_student" value="1">
                    <input type="hidden" id="editStudentID" name="studentID">
                    <label for="editLastName">Фамилия:</label>
                    <input type="text" id="editLastName" name="lastName" required>

                    <label for="editFirstName">Имя:</label>
                    <input type="text" id="editFirstName" name="firstName" required>

                    <label for="editMiddleName">Отчество:</label>
                    <input type="text" id="editMiddleName" name="middleName" required>

                    <label for="editBirthDate">Дата рождения:</label>
                    <input type="date" id="editBirthDate" name="birthDate" required>

                    <label for="editGender">Пол:</label>
                    <input type="text" id="editGender" name="gender" required>

                    <label for="editContactNumber">Контактный номер:</label>
                    <input type="text" id="editContactNumber" name="contactNumber" required>

                    <label for="editEducationLevel">Образование:</label>
                    <input type="text" id="editEducationLevel" name="educationLevel" required>

                    <label for="editDepartment">Отделение:</label>
                    <input type="text" id="editDepartment" name="department" required>

                    <label for="editGroupName">Группа:</label>
                    <input type="text" id="editGroupName" name="groupName" required>

                    <label for="editFundingType">Финансирование:</label>
                    <input type="text" id="editFundingType" name="fundingType" required>

                    <label for="editAdmissionYear">Год поступления:</label>
                    <input type="text" id="editAdmissionYear" name="admissionYear" required>

                    <label for="editGraduationYear">Год окончания:</label>
                    <input type="text" id="editGraduationYear" name="graduationYear" required>

                    <label for="editDismissalInfo">Информация об отчислении:</label>
                    <input type="text" id="editDismissalInfo" name="dismissalInfo">

                    <label for="editDismissalDate">Дата отчисления:</label>
                    <input type="date" id="editDismissalDate" name="dismissalDate">

                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="notes">

                    <button type="submit" class="button button-blue">Сохранить</button>
                </form>
            </div>

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
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <th>Действия</th>
                        <?php endif; ?>
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
                                <td>{$row['Notes']}</td>";
                            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                                 echo "<td>
                                    <button onclick='showEditForm({$row['StudentID']}, \"{$row['LastName']}\", \"{$row['FirstName']}\", \"{$row['MiddleName']}\", \"{$row['BirthDate']}\", \"{$row['Gender']}\", \"{$row['ContactNumber']}\", \"{$row['EducationLevel']}\", \"{$row['Department']}\", \"{$row['GroupName']}\", \"{$row['FundingType']}\", \"{$row['AdmissionYear']}\", \"{$row['GraduationYear']}\", \"{$row['DismissalInfo']}\", \"{$row['DismissalDate']}\", \"{$row['Notes']}\")' class='button button-blue'>Редактировать</button>
                                    <button class='button button-red'><a href='students.php?delete_id={$row['StudentID']}'>Удалить</a></button>
                                </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='15'>Нет данных о студентах</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; Моисеев и Омельченко, 2025 Информационная система учебного заведения</p>
    </footer>
    <script>
        function showAddForm() {
            document.getElementById('addForm').style.display = 'block';
        }

        function showEditForm(studentID, lastName, firstName, middleName, birthDate, gender, contactNumber, educationLevel, department, groupName, fundingType, admissionYear, graduationYear, dismissalInfo, dismissalDate, notes) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editLastName').value = lastName;
            document.getElementById('editFirstName').value = firstName;
            document.getElementById('editMiddleName').value = middleName;
            document.getElementById('editBirthDate').value = birthDate;
            document.getElementById('editGender').value = gender;
            document.getElementById('editContactNumber').value = contactNumber;
            document.getElementById('editEducationLevel').value = educationLevel;
            document.getElementById('editDepartment').value = department;
            document.getElementById('editGroupName').value = groupName;
            document.getElementById('editFundingType').value = fundingType;
            document.getElementById('editAdmissionYear').value = admissionYear;
            document.getElementById('editGraduationYear').value = graduationYear;
            document.getElementById('editDismissalInfo').value = dismissalInfo;
            document.getElementById('editDismissalDate').value = dismissalDate;
            document.getElementById('editNotes').value = notes;
        }
    </script>
</body>
</html>
