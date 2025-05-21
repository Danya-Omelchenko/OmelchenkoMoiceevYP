<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инвалиды</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Инвалиды</h1>
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
            <li><a href="disabled.php" class="active">Инвалиды</a></li>
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
            <h2>Студенты с инвалидностью</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить запись</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента">

                <label for="statusOrder">Приказ о присвоении статуса:</label>
                <input type="text" id="statusOrder" name="statusOrder" placeholder="Введите приказ">

                <label for="statusStart">Начало статуса:</label>
                <input type="date" id="statusStart" name="statusStart">

                <label for="statusEnd">Конец статуса:</label>
                <input type="date" id="statusEnd" name="statusEnd">

                <label for="disabilityType">Вид инвалидности:</label>
                <input type="text" id="disabilityType" name="disabilityType" placeholder="Введите вид инвалидности">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание">

                <button type="button" onclick="loadDisabledStudents()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Форма добавления -->
            <div id="addForm" style="display: none;">
                <h3>Добавить запись об инвалидности</h3>
                <form id="addDisabledForm">
                    <input type="hidden" name="add_disabledStudent" value="1">

                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>

                    <label for="addStatusOrder">Приказ о присвоении статуса:</label>
                    <input type="text" id="addStatusOrder" name="StatusOrder">

                    <label for="addStatusStart">Начало статуса:</label>
                    <input type="date" id="addStatusStart" name="StatusStart" required>

                    <label for="addStatusEnd">Конец статуса:</label>
                    <input type="date" id="addStatusEnd" name="StatusEnd" required>

                    <label for="addDisabilityType">Вид инвалидности:</label>
                    <input type="text" id="addDisabilityType" name="DisabilityType">

                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">

                    <button type="button" onclick="addDisabledStudent()" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма редактирования -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать запись об инвалидности</h3>
                <form id="editDisabledForm">
                    <input type="hidden" name="edit_disabledStudent" value="1">
                    <input type="hidden" id="editDisabledStudentID" name="DisabledStudentID">

                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>

                    <label for="editStatusOrder">Приказ о присвоении статуса:</label>
                    <input type="text" id="editStatusOrder" name="StatusOrder">

                    <label for="editStatusStart">Начало статуса:</label>
                    <input type="date" id="editStatusStart" name="StatusStart" required>

                    <label for="editStatusEnd">Конец статуса:</label>
                    <input type="date" id="editStatusEnd" name="StatusEnd" required>

                    <label for="editDisabilityType">Вид инвалидности:</label>
                    <input type="text" id="editDisabilityType" name="DisabilityType">

                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">

                    <button type="button" onclick="updateDisabledStudent()" class="button button-blue">Сохранить</button>
                </form>
            </div>

            <!-- Таблица с данными -->
            <table id="disabledTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID студента</th>
                        <th>Приказ</th>
                        <th>Начало статуса</th>
                        <th>Конец статуса</th>
                        <th>Вид инвалидности</th>
                        <th>Примечание</th>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <th>Действия</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Данные загружаются через AJAX -->
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
            document.getElementById('editForm').style.display = 'none';
        }

        function showEditForm(disabledStudentID, studentID, statusOrder, statusStart, statusEnd, disabilityType, notes) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('addForm').style.display = 'none';

            document.getElementById('editDisabledStudentID').value = disabledStudentID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editStatusOrder').value = statusOrder;
            document.getElementById('editStatusStart').value = statusStart;
            document.getElementById('editStatusEnd').value = statusEnd;
            document.getElementById('editDisabilityType').value = disabilityType;
            document.getElementById('editNotes').value = notes;
        }

        function addDisabledStudent() {
            const formData = $('#addDisabledForm').serialize();

            $.ajax({
                url: 'Controllers/disabled_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadDisabledStudents();
                        document.getElementById('addForm').style.display = 'none';
                        $('#addDisabledForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateDisabledStudent() {
            const formData = $('#editDisabledForm').serialize();

            $.ajax({
                url: 'Controllers/disabled_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadDisabledStudents();
                        document.getElementById('editForm').style.display = 'none';
                    } else {
                        alert(result.error || 'Ошибка при обновлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteDisabledStudent(disabledStudentID) {
            if (confirm('Вы уверены, что хотите удалить эту запись?')) {
                $.ajax({
                    url: 'Controllers/disabled_controller.php',
                    type: 'GET',
                    data: { delete_id: disabledStudentID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadDisabledStudents();
                        } else {
                            alert(result.error || 'Ошибка при удалении записи');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Ошибка: ' + error);
                    }
                });
            }
        }

        function loadDisabledStudents() {
            $.ajax({
                url: 'Controllers/disabled_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(student) {
                            tableBody += `
                                <tr>
                                    <td>${student.DisabledStudentID || ''}</td>
                                    <td>${student.LastName || ''} ${student.FirstName || ''} ${student.MiddleName || ''} (ID: ${student.StudentID || ''})</td>
                                    <td>${student.StatusOrder || ''}</td>
                                    <td>${student.StatusStart || ''}</td>
                                    <td>${student.StatusEnd || ''}</td>
                                    <td>${student.DisabilityType || ''}</td>
                                    <td>${student.Notes || ''}</td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${student.DisabledStudentID},
                                            ${student.StudentID},
                                            '${escapeSingleQuote(student.StatusOrder)}',
                                            '${student.StatusStart}',
                                            '${student.StatusEnd}',
                                            '${escapeSingleQuote(student.DisabilityType)}',
                                            '${escapeSingleQuote(student.Notes)}'
                                        )" class="button button-blue">Редактировать</button>                                    </td>
                                    <?php endif; ?>
                                </tr>
                            `;
                        });
                        $('#disabledTable tbody').html(tableBody);
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

        function escapeSingleQuote(str) {
            return (str || '').replace(/'/g, "\\'");
        }

        $(document).ready(function() {
            loadDisabledStudents();
        });
    </script>
</body>
</html>