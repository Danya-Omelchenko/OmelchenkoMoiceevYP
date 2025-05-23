<?php
require_once "auth_check.php";
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
            <li><a href="students.php" class="active">Студенты</a></li>
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
            <?php
            require_once "auth_check.php";
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить студента</button>
            <?php
            require_once "auth_check.php";
            endif; ?>

            <!-- Форма для фильтров -->
            <form id="filterForm" method="get" action="students.php">
                <div class="form-row">
                    <label for="search">Поиск по имени или фамилии:</label>
                    <input type="text" id="search" name="search" placeholder="Введите имя или фамилию">
                </div>

                <div class="form-row">
                    <label for="group">Группа:</label>
                    <input type="text" id="group" name="group" placeholder="Введите группу">
                </div>

                <div class="form-row">
                    <label for="department">Отделение:</label>
                    <input type="text" id="department" name="department" placeholder="Введите отделение">
                </div>

                <div class="form-row">
                    <label for="fundingType">Финансирование:</label>
                    <input type="text" id="fundingType" name="fundingType" placeholder="Введите тип финансирования">
                </div>

                <div class="form-row">
                    <label for="admissionYear">Год поступления:</label>
                    <input type="text" id="admissionYear" name="admissionYear" placeholder="Введите год поступления">
                </div>

                <div class="form-row">
                    <label for="graduationYear">Год окончания:</label>
                    <input type="text" id="graduationYear" name="graduationYear" placeholder="Введите год окончания">
                </div>

                <div class="form-row">
                    <label for="educationLevel">Образование:</label>
                    <input type="text" id="educationLevel" name="educationLevel" placeholder="Введите уровень образования">
                </div>

                <div class="form-row">
                    <label for="gender">Пол:</label>
                    <input type="text" id="gender" name="gender" placeholder="Введите пол">
                </div>

                <button type="submit" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления студента -->
            <div id="addModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить студента</h3>
                    <form id="addStudentForm">
                        <input type="hidden" name="add_student" value="1">

                        <div class="form-row">
                            <label for="lastName">Фамилия:</label>
                            <input type="text" id="lastName" name="LastName" required>
                        </div>

                        <div class="form-row">
                            <label for="firstName">Имя:</label>
                            <input type="text" id="firstName" name="FirstName" required>
                        </div>

                        <div class="form-row">
                            <label for="middleName">Отчество:</label>
                            <input type="text" id="middleName" name="MiddleName" required>
                        </div>

                        <div class="form-row">
                            <label for="birthDate">Дата рождения:</label>
                            <input type="date" id="birthDate" name="BirthDate" required>
                        </div>

                        <div class="form-row">
                            <label for="gender">Пол:</label>
                            <input type="text" id="gender" name="Gender" required>
                        </div>

                        <div class="form-row">
                            <label for="contactNumber">Контактный номер:</label>
                            <input type="text" id="contactNumber" name="ContactNumber" required>
                        </div>

                        <div class="form-row">
                            <label for="educationLevel">Образование:</label>
                            <input type="text" id="educationLevel" name="EducationLevel" required>
                        </div>

                        <div class="form-row">
                            <label for="department">Отделение:</label>
                            <input type="text" id="department" name="Department" required>
                        </div>

                        <div class="form-row">
                            <label for="groupName">Группа:</label>
                            <input type="text" id="groupName" name="GroupName" required>
                        </div>

                        <div class="form-row">
                            <label for="fundingType">Финансирование:</label>
                            <input type="text" id="fundingType" name="FundingType" required>
                        </div>

                        <div class="form-row">
                            <label for="admissionYear">Год поступления:</label>
                            <input type="text" id="admissionYear" name="AdmissionYear" required>
                        </div>

                        <div class="form-row">
                            <label for="graduationYear">Год окончания:</label>
                            <input type="text" id="graduationYear" name="GraduationYear">
                        </div>

                        <div class="form-row">
                            <label for="dismissalInfo">Информация об отчислении:</label>
                            <input type="text" id="dismissalInfo" name="DismissalInfo">
                        </div>

                        <div class="form-row">
                            <label for="dismissalDate">Дата отчисления:</label>
                            <input type="date" id="dismissalDate" name="DismissalDate">
                        </div>

                        <div class="form-row">
                            <label for="notes">Примечание:</label>
                            <input type="text" id="notes" name="Notes">
                        </div>

                        <div class="form-row">
                            <label for="parentsInfo">Информация о родителях:</label>
                            <input type="text" id="parentsInfo" name="ParentsInfo">
                        </div>

                        <div class="form-row">
                            <label for="penalties">Штрафы:</label>
                            <input type="text" id="penalties" name="Penalties">
                        </div>

                        <button type="button" onclick="addStudent()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования студента -->
            <div id="editModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать студента</h3>
                    <form id="editStudentForm">
                        <input type="hidden" name="edit_student" value="1">
                        <input type="hidden" id="editStudentID" name="StudentID">

                        <div class="form-row">
                            <label for="editLastName">Фамилия:</label>
                            <input type="text" id="editLastName" name="LastName" required>
                        </div>

                        <div class="form-row">
                            <label for="editFirstName">Имя:</label>
                            <input type="text" id="editFirstName" name="FirstName" required>
                        </div>

                        <div class="form-row">
                            <label for="editMiddleName">Отчество:</label>
                            <input type="text" id="editMiddleName" name="MiddleName" required>
                        </div>

                        <div class="form-row">
                            <label for="editBirthDate">Дата рождения:</label>
                            <input type="date" id="editBirthDate" name="BirthDate" required>
                        </div>

                        <div class="form-row">
                            <label for="editGender">Пол:</label>
                            <input type="text" id="editGender" name="Gender" required>
                        </div>

                        <div class="form-row">
                            <label for="editContactNumber">Контактный номер:</label>
                            <input type="text" id="editContactNumber" name="ContactNumber" required>
                        </div>

                        <div class="form-row">
                            <label for="editEducationLevel">Образование:</label>
                            <input type="text" id="editEducationLevel" name="EducationLevel" required>
                        </div>

                        <div class="form-row">
                            <label for="editDepartment">Отделение:</label>
                            <input type="text" id="editDepartment" name="Department" required>
                        </div>

                        <div class="form-row">
                            <label for="editGroupName">Группа:</label>
                            <input type="text" id="editGroupName" name="GroupName" required>
                        </div>

                        <div class="form-row">
                            <label for="editFundingType">Финансирование:</label>
                            <input type="text" id="editFundingType" name="FundingType" required>
                        </div>

                        <div class="form-row">
                            <label for="editAdmissionYear">Год поступления:</label>
                            <input type="text" id="editAdmissionYear" name="AdmissionYear" required>
                        </div>

                        <div class="form-row">
                            <label for="editGraduationYear">Год окончания:</label>
                            <input type="text" id="editGraduationYear" name="GraduationYear">
                        </div>

                        <div class="form-row">
                            <label for="editDismissalInfo">Информация об отчислении:</label>
                            <input type="text" id="editDismissalInfo" name="DismissalInfo">
                        </div>

                        <div class="form-row">
                            <label for="editDismissalDate">Дата отчисления:</label>
                            <input type="date" id="editDismissalDate" name="DismissalDate">
                        </div>

                        <div class="form-row">
                            <label for="editNotes">Примечание:</label>
                            <input type="text" id="editNotes" name="Notes">
                        </div>

                        <div class="form-row">
                            <label for="editParentsInfo">Информация о родителях:</label>
                            <input type="text" id="editParentsInfo" name="ParentsInfo">
                        </div>

                        <div class="form-row">
                            <label for="editPenalties">Штрафы:</label>
                            <input type="text" id="editPenalties" name="Penalties">
                        </div>

                        <button type="button" onclick="editStudent()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Таблица со списком студентов -->
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
                        <th>Финанс.</th>
                        <th>Год поступ.</th>
                        <th>Год оконч.</th>
                        <th>Информация об отчислении</th>
                        <th>Дата отчисления</th>
                        <th>Примечание</th>
                        <th>Информация о родителях</th>
                        <th>Штрафы</th>
                        <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <th>Действия</th>
                        <?php
require_once "auth_check.php";
 endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Данные будут загружены через AJAX -->
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; Моисеев и Омельченко, 2025 Информационная система учебного заведения</p>
    </footer>

    <script>
        function showAddForm() {
            document.getElementById('addModal').style.display = 'block';
        }

        function showEditForm(studentID, lastName, firstName, middleName, birthDate,
        gender, contactNumber, educationLevel, department, groupName, fundingType,
        admissionYear, graduationYear, dismissalInfo, dismissalDate, notes, parentsInfo, penalties) {
            document.getElementById('editModal').style.display = 'block';

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

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Закрытие модального окна при клике вне его области
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        };

        function addStudent() {
            const formData = $('#addStudentForm').serialize();

            $.ajax({
                url: 'Controllers/students_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        location.reload();
                    } else {
                        alert(result.error || 'Ошибка при добавлении студента');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function editStudent() {
            const formData = $('#editStudentForm').serialize();

            $.ajax({
                url: 'Controllers/students_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('editModal');
                        location.reload();
                    } else {
                        alert(result.error || 'Ошибка при обновлении студента');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteStudent(studentID) {
            if (confirm('Вы уверены, что хотите удалить этого студента?')) {
                $.ajax({
                    url: 'Controllers/students_controller.php',
                    type: 'GET',
                    data: { delete_id: studentID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            location.reload();
                        } else {
                            alert(result.error || 'Ошибка при удалении студента');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Ошибка: ' + error);
                    }
                });
            }
        }

       $(document).ready(function() {
        // Функция загрузки данных
        function loadStudents() {
            $.ajax({
                url: 'Controllers/students_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(student) {
                            tableBody += `
                                <tr>
                                    <td>${student.LastName || ''}</td>
                                    <td>${student.FirstName || ''}</td>
                                    <td>${student.MiddleName || ''}</td>
                                    <td>${student.BirthDate || ''}</td>
                                    <td>${student.Gender || ''}</td>
                                    <td>${student.ContactNumber || ''}</td>
                                    <td>${student.EducationLevel || ''}</td>
                                    <td>${student.Department || ''}</td>
                                    <td>${student.GroupName || ''}</td>
                                    <td>${student.FundingType || ''}</td>
                                    <td>${student.AdmissionYear || ''}</td>
                                    <td>${student.GraduationYear || ''}</td>
                                    <td>${student.DismissalInfo || ''}</td>
                                    <td>${student.DismissalDate || ''}</td>
                                    <td>${student.Notes || ''}</td>
                                    <td>${student.ParentsInfo || ''}</td>
                                    <td>${student.Penalties || ''}</td>
                                    <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${student.StudentID},
                                            '${escapeSingleQuote(student.LastName)}',
                                            '${escapeSingleQuote(student.FirstName)}',
                                            '${escapeSingleQuote(student.MiddleName)}',
                                            '${student.BirthDate}',
                                            '${escapeSingleQuote(student.Gender)}',
                                            '${escapeSingleQuote(student.ContactNumber)}',
                                            '${escapeSingleQuote(student.EducationLevel)}',
                                            '${escapeSingleQuote(student.Department)}',
                                            '${escapeSingleQuote(student.GroupName)}',
                                            '${escapeSingleQuote(student.FundingType)}',
                                            '${student.AdmissionYear}',
                                            '${student.GraduationYear}',
                                            '${escapeSingleQuote(student.DismissalInfo)}',
                                            '${student.DismissalDate}',
                                            '${escapeSingleQuote(student.Notes)}',
                                            '${escapeSingleQuote(student.ParentsInfo)}',
                                            '${escapeSingleQuote(student.Penalties)}'
                                        )" class="button button-blue">Редактировать</button>
                                        <button onclick="deleteStudent(${student.StudentID})" class="button button-red">Удалить</button>
                                    </td>
                                    <?php
require_once "auth_check.php";
 endif; ?>
                                </tr>
                            `;
                        });
                        $('#studentsTable tbody').html(tableBody);
                    } else {
                        alert(response.error || 'Ошибка при загрузке данных');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Ошибка при загрузке данных: ' + error);
                }
            });
        }

        // Функция для экранирования кавычек
        window.escapeSingleQuote = function(str) {
            return (str || '').replace(/'/g, "\\'");
        }

        // Загрузка данных при старте страницы
        loadStudents();

        // Обработка отправки формы фильтрации
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            loadStudents();
        });
    });
    </script>
</body>
</html>
