<?php
session_start();
include 'db_connection.php'; // Подключение к базе данных
include 'models/StudentManager.php'; // Подключение класса управления студентами

$studentManager = new StudentManager($conn);

// Обработка добавления студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    try {
        $student = new Student($_POST);
        $result = $studentManager->addStudent($student);
        if ($result) {
            echo "Студент успешно добавлен";
        } else {
            echo "Ошибка при добавлении студента: " . $studentManager->getError();
        }
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage();
    }
    exit();
}

// Обработка редактирования студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_student'])) {
    try {
        $student = new Student($_POST);
        $result = $studentManager->editStudent($student);
        if ($result) {
            echo "Студент успешно отредактирован";
        } else {
            echo "Ошибка при редактировании студента: " . $studentManager->getError();
        }
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage();
    }
    exit();
}

// Обработка удаления студента
if (isset($_GET['delete_id'])) {
    try {
        $result = $studentManager->deleteStudent($_GET['delete_id']);
        if ($result) {
            echo "Студент успешно удален";
        } else {
            echo "Ошибка при удалении студента: " . $studentManager->getError();
        }
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage();
    }
    exit();
}

// Получение списка студентов
$students = $studentManager->getStudents();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список студентов</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            <!-- Форма для фильтров -->
            <form id="filterForm" method="get" action="students.php">
                <label for="search">Поиск по имени или фамилии:</label>
                <input type="text" id="search" name="search" placeholder="Введите имя или фамилию">

                <label for="group">Группа:</label>
                <input type="text" id="group" name="group" placeholder="Введите группу">

                <label for="department">Отделение:</label>
                <input type="text" id="department" name="department" placeholder="Введите отделение">

                <label for="fundingType">Финансирование:</label>
                <input type="text" id="fundingType" name="fundingType" placeholder="Введите тип финансирования">

                <label for="admissionYear">Год поступления:</label>
                <input type="text" id="admissionYear" name="admissionYear" placeholder="Введите год поступления">

                <label for="graduationYear">Год окончания:</label>
                <input type="text" id="graduationYear" name="graduationYear" placeholder="Введите год окончания">

                <label for="educationLevel">Образование:</label>
                <input type="text" id="educationLevel" name="educationLevel" placeholder="Введите уровень образования">

                <label for="gender">Пол:</label>
                <input type="text" id="gender" name="gender" placeholder="Введите пол">

                <button type="submit" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Форма для добавления студента -->
            <div id="addForm" style="display: none;">
                <h3>Добавить студента</h3>
                <form id="addStudentForm" method="post">
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

                    <label for="parentsInfo">Информация о родителях:</label>
                    <input type="text" id="parentsInfo" name="parentsInfo">

                    <label for="penalties">Штрафы:</label>
                    <input type="text" id="penalties" name="penalties">

                    <button type="button" onclick="addStudent()" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма для редактирования студента -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать студента</h3>
                <form id="editStudentForm" method="post">
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

                    <label for="editParentsInfo">Информация о родителях:</label>
                    <input type="text" id="editParentsInfo" name="parentsInfo">

                    <label for="editPenalties">Штрафы:</label>
                    <input type="text" id="editPenalties" name="penalties">

                    <button type="button" onclick="editStudent()" class="button button-blue">Сохранить</button>
                </form>
            </div>

            <table id="studentsTable">
                <thead>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Дата рождения</th>
                        <th>Пол</th>
                        <th>Контактный номер</th>
                        <th>Образование</th>
                        <th>Отд.</th>
                        <th>Группа</th>
                        <th>Финансиров.</th>
                        <th>Год поступл</th>
                        <th>Год оконч.</th>
                        <th>Информация об отчислении</th>
                        <th>Дата отчисления</th>
                        <th>Примечание</th>
                        <th>Информация о родителях</th>
                        <th>Штрафы</th>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <th>Действия</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student->lastName); ?></td>
                            <td><?php echo htmlspecialchars($student->firstName); ?></td>
                            <td><?php echo htmlspecialchars($student->middleName); ?></td>
                            <td><?php echo htmlspecialchars($student->birthDate); ?></td>
                            <td><?php echo htmlspecialchars($student->gender); ?></td>
                            <td><?php echo htmlspecialchars($student->contactNumber); ?></td>
                            <td><?php echo htmlspecialchars($student->educationLevel); ?></td>
                            <td><?php echo htmlspecialchars($student->department); ?></td>
                            <td><?php echo htmlspecialchars($student->groupName); ?></td>
                            <td><?php echo htmlspecialchars($student->fundingType); ?></td>
                            <td><?php echo htmlspecialchars($student->admissionYear); ?></td>
                            <td><?php echo htmlspecialchars($student->graduationYear); ?></td>
                            <td><?php echo htmlspecialchars($student->dismissalInfo); ?></td>
                            <td><?php echo htmlspecialchars($student->dismissalDate); ?></td>
                            <td><?php echo htmlspecialchars($student->notes); ?></td>
                            <td><?php echo htmlspecialchars($student->parentsInfo); ?></td>
                            <td><?php echo htmlspecialchars($student->penalties); ?></td>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <td>
                                    <button onclick='showEditForm(<?php echo $student->studentID; ?>, "<?php echo htmlspecialchars($student->lastName); ?>", "<?php echo htmlspecialchars($student->firstName); ?>", "<?php echo htmlspecialchars($student->middleName); ?>", "<?php echo htmlspecialchars($student->birthDate); ?>", "<?php echo htmlspecialchars($student->gender); ?>", "<?php echo htmlspecialchars($student->contactNumber); ?>", "<?php echo htmlspecialchars($student->educationLevel); ?>", "<?php echo htmlspecialchars($student->department); ?>", "<?php echo htmlspecialchars($student->groupName); ?>", "<?php echo htmlspecialchars($student->fundingType); ?>", "<?php echo htmlspecialchars($student->admissionYear); ?>", "<?php echo htmlspecialchars($student->graduationYear); ?>", "<?php echo htmlspecialchars($student->dismissalInfo); ?>", "<?php echo htmlspecialchars($student->dismissalDate); ?>", "<?php echo htmlspecialchars($student->notes); ?>", "<?php echo htmlspecialchars($student->parentsInfo); ?>", "<?php echo htmlspecialchars($student->penalties); ?>")' class='button button-blue'>Редактировать</button>
                                    <button onclick='deleteStudent(<?php echo $student->studentID; ?>)' class='button button-red'>Удалить</button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
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

        function showEditForm(studentID, lastName, firstName, middleName, birthDate, gender, contactNumber, educationLevel, department, groupName, fundingType, admissionYear, graduationYear, dismissalInfo, dismissalDate, notes, parentsInfo, penalties) {
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
            document.getElementById('editParentsInfo').value = parentsInfo;
            document.getElementById('editPenalties').value = penalties;
        }

        function addStudent() {
            var formData = $('#addStudentForm').serialize();
            $.ajax({
                url: 'students.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert(response);
                    $('#addForm').hide();
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function editStudent() {
            var formData = $('#editStudentForm').serialize();
            $.ajax({
                url: 'students.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert(response);
                    $('#editForm').hide();
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function deleteStudent(studentID) {
            if (confirm('Вы уверены, что хотите удалить этого студента?')) {
                $.ajax({
                    url: 'students.php',
                    type: 'GET',
                    data: { delete_id: studentID },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        }
    </script>
</body>
</html>
