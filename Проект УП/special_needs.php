<?php
require_once "auth_check.php";


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ОВЗ</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>ОВЗ</h1>
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
            <li><a href="disabled.php">Инвалиды</a></li>
            <li><a href="special_needs.php" class="active">ОВЗ</a></li>
            <li><a href="dormitories.php">Общежитие</a></li>
            <li><a href="risk_groups.php">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Список студентов с ОВЗ</h2>

            <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить статус ОВЗ</button>
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
                    <label for="notes">Примечание:</label>
                    <input type="text" id="notes" name="notes" placeholder="Введите примечание">
                </div>

                <button type="button" onclick="loadSpecialNeeds()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления статуса ОВЗ -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить статус ОВЗ</h3>
                    <form id="addSpecialNeedsForm">
                        <input type="hidden" name="add_special_needs" value="1">

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
                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">
                </div>

                        <button type="button" onclick="addSpecialNeeds()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования статуса ОВЗ -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать статус ОВЗ</h3>
                    <form id="editSpecialNeedsForm">
                        <input type="hidden" name="edit_special_needs" value="1">
                        <input type="hidden" id="editSpecialNeedsStudentID" name="SpecialNeedsStudentID">

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
                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">
                </div>

                        <button type="button" onclick="updateSpecialNeeds()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Таблица с данными -->
            <table id="specialNeedsTable">
                <thead>
                    <tr>
                        <th>ID студента</th>
                        <th>Приказ о присвоении статуса</th>
                        <th>Начало статуса</th>
                        <th>Конец статуса</th>
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
                    <!-- Данные будут загружаться через AJAX -->
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

        function showEditForm(specialNeedsStudentID, studentID, statusOrder, statusStart, statusEnd, notes) {
            document.getElementById('editModal').style.display = 'block';

            document.getElementById('editSpecialNeedsStudentID').value = specialNeedsStudentID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editStatusOrder').value = statusOrder;
            document.getElementById('editStatusStart').value = statusStart;
            document.getElementById('editStatusEnd').value = statusEnd;
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

        function addSpecialNeeds() {
            const formData = $('#addSpecialNeedsForm').serialize();

            $.ajax({
                url: 'Controllers/special_needs_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadSpecialNeeds();
                        $('#addSpecialNeedsForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении статуса ОВЗ');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateSpecialNeeds() {
            const formData = $('#editSpecialNeedsForm').serialize();

            $.ajax({
                url: 'Controllers/special_needs_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('editModal');
                        loadSpecialNeeds();
                    } else {
                        alert(result.error || 'Ошибка при обновлении статуса ОВЗ');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteSpecialNeeds(specialNeedsStudentID) {
            if (confirm('Вы уверены, что хотите удалить этот статус ОВЗ?')) {
                $.ajax({
                    url: 'Controllers/special_needs_controller.php',
                    type: 'GET',
                    data: { delete_id: specialNeedsStudentID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadSpecialNeeds();
                        } else {
                            alert(result.error || 'Ошибка при удалении статуса ОВЗ');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Ошибка: ' + error);
                    }
                });
            }
        }

        function loadSpecialNeeds() {
            $.ajax({
                url: 'Controllers/special_needs_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(specialNeeds) {
                            tableBody += `
                                <tr>
                                    <td>${specialNeeds.StudentID || ''}</td>
                                    <td>${specialNeeds.StatusOrder || ''}</td>
                                    <td>${specialNeeds.StatusStart || ''}</td>
                                    <td>${specialNeeds.StatusEnd || ''}</td>
                                    <td>${specialNeeds.Notes || ''}</td>
                                    <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${specialNeeds.SpecialNeedsStudentID},
                                            ${specialNeeds.StudentID},
                                            '${specialNeeds.StatusOrder ? specialNeeds.StatusOrder.replace(/'/g, "\\'") : ''}',
                                            '${specialNeeds.StatusStart ? specialNeeds.StatusStart.replace(/'/g, "\\'") : ''}',
                                            '${specialNeeds.StatusEnd ? specialNeeds.StatusEnd.replace(/'/g, "\\'") : ''}',
                                            '${specialNeeds.Notes ? specialNeeds.Notes.replace(/'/g, "\\'") : ''}'
                                        )" class="button button-blue">Редактировать</button>
                                        <button onclick="deleteSpecialNeeds(${specialNeeds.SpecialNeedsStudentID})" class="button button-red">Удалить</button>
                                    </td>
                                    <?php
require_once "auth_check.php";
 endif; ?>
                                </tr>
                            `;
                        });
                        $('#specialNeedsTable tbody').html(tableBody);
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

        $(document).ready(function() {
            loadSpecialNeeds();
        });
    </script>
</body>
</html>
