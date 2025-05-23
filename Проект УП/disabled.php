<?php
require_once "auth_check.php";


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

            <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить запись</button>
            <?php
require_once "auth_check.php";
 endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <div class="form-row">
                    <label for="studentID">ID студента:</label>
                    <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента">
                </div>

                <div class="form-row">
                    <label for="statusOrder">Приказ о присвоении статуса:</label>
                    <input type="text" id="statusOrder" name="statusOrder" placeholder="Введите приказ">
                </div>

                <div class="form-row">
                    <label for="statusStart">Начало статуса:</label>
                    <input type="date" id="statusStart" name="statusStart">
                </div>

                <div class="form-row">
                    <label for="statusEnd">Конец статуса:</label>
                    <input type="date" id="statusEnd" name="statusEnd">
                </div>

                <div class="form-row">
                    <label for="disabilityType">Вид инвалидности:</label>
                    <input type="text" id="disabilityType" name="disabilityType" placeholder="Введите вид инвалидности">
                </div>

                <div class="form-row">
                    <label for="notes">Примечание:</label>
                    <input type="text" id="notes" name="notes" placeholder="Введите примечание">
                </div>

                <button type="button" onclick="loadDisabledStudents()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления записи -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить запись об инвалидности</h3>
                    <form id="addDisabledForm">
                        <input type="hidden" name="add_disabledStudent" value="1">

                        <div class="form-row">
                            <label for="addStudentID">ID студента:</label>
                            <input type="number" id="addStudentID" name="StudentID" required>
                        </div>

                        <div class="form-row">
                            <label for="addStatusOrder">Приказ о присвоении статуса:</label>
                            <input type="text" id="addStatusOrder" name="StatusOrder">
                        </div>

                        <div class="form-row">
                            <label for="addStatusStart">Начало статуса:</label>
                            <input type="date" id="addStatusStart" name="StatusStart" required>
                        </div>

                        <div class="form-row">
                            <label for="addStatusEnd">Конец статуса:</label>
                            <input type="date" id="addStatusEnd" name="StatusEnd" required>
                        </div>

                        <div class="form-row">
                            <label for="addDisabilityType">Вид инвалидности:</label>
                            <input type="text" id="addDisabilityType" name="DisabilityType">
                        </div>

                        <div class="form-row">
                            <label for="addNotes">Примечание:</label>
                            <input type="text" id="addNotes" name="Notes">
                        </div>

                        <button type="button" onclick="addDisabledStudent()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования записи -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать запись об инвалидности</h3>
                    <form id="editDisabledForm">
                        <input type="hidden" name="edit_disabledStudent" value="1">
                        <input type="hidden" id="editDisabledStudentID" name="DisabledStudentID">

                        <div class="form-row">
                            <label for="editStudentID">ID студента:</label>
                            <input type="number" id="editStudentID" name="StudentID" required>
                        </div>

                        <div class="form-row">
                            <label for="editStatusOrder">Приказ о присвоении статуса:</label>
                            <input type="text" id="editStatusOrder" name="StatusOrder">
                        </div>

                        <div class="form-row">
                            <label for="editStatusStart">Начало статуса:</label>
                            <input type="date" id="editStatusStart" name="StatusStart" required>
                        </div>

                        <div class="form-row">
                            <label for="editStatusEnd">Конец статуса:</label>
                            <input type="date" id="editStatusEnd" name="StatusEnd" required>
                        </div>

                        <div class="form-row">
                            <label for="editDisabilityType">Вид инвалидности:</label>
                            <input type="text" id="editDisabilityType" name="DisabilityType">
                        </div>

                        <div class="form-row">
                            <label for="editNotes">Примечание:</label>
                            <input type="text" id="editNotes" name="Notes">
                        </div>

                        <button type="button" onclick="updateDisabledStudent()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
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
            document.getElementById('addModal').style.display = 'block';
        }

        function showEditForm(disabledStudentID, studentID, statusOrder, statusStart, statusEnd, disabilityType, notes) {
            document.getElementById('editModal').style.display = 'block';

            document.getElementById('editDisabledStudentID').value = disabledStudentID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editStatusOrder').value = statusOrder;
            document.getElementById('editStatusStart').value = statusStart;
            document.getElementById('editStatusEnd').value = statusEnd;
            document.getElementById('editDisabilityType').value = disabilityType;
            document.getElementById('editNotes').value = notes;
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

        function addDisabledStudent() {
            const formData = $('#addDisabledForm').serialize();

            $.ajax({
                url: 'Controllers/disabled_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadDisabledStudents();
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
                        closeModal('editModal');
                        loadDisabledStudents();
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
                                    <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${student.DisabledStudentID},
                                            ${student.StudentID},
                                            '${escapeSingleQuote(student.StatusOrder)}',
                                            '${student.StatusStart}',
                                            '${student.StatusEnd}',
                                            '${escapeSingleQuote(student.DisabilityType)}',
                                            '${escapeSingleQuote(student.Notes)}'
                                        )" class="button button-blue">Редактировать</button>
                                        <button onclick="deleteDisabledStudent(${student.DisabledStudentID})" class="button button-red">Удалить</button>
                                    </td>
                                    <?php
require_once "auth_check.php";
 endif; ?>
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
