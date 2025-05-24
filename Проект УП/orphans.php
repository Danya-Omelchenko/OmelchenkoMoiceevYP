<?php
require_once "auth_check.php";


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сироты</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Сироты</h1>
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
            <li><a href="departments.php">Отделения</a></li>
            <li><a href="orphans.php" class="active">Сироты</a></li>
            <li><a href="disabled.php">Инвалиды</a></li>
            <li><a href="special_needs.php">ОВЗ</a></li>
            <li><a href="dormitories.php">Общежитие</a></li>
            <li><a href="rooms.php">Комнаты</a></li>
            <li><a href="risk_groups.php">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Студенты-сироты</h2>

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
                    <label for="notes">Примечание:</label>
                    <input type="text" id="notes" name="notes" placeholder="Введите примечание">
                </div>

                <button type="button" onclick="loadOrphans()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления записи -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить запись о сироте</h3>
                    <form id="addOrphanForm">
                        <input type="hidden" name="add_orphan" value="1">

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

                        <button type="button" onclick="addOrphan()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования записи -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать запись о сироте</h3>
                    <form id="editOrphanForm">
                        <input type="hidden" name="edit_orphan" value="1">
                        <input type="hidden" id="editOrphanID" name="OrphanID">

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

                        <button type="button" onclick="updateOrphan()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Таблица с данными -->
            <table id="orphansTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Студент</th>
                        <th>Приказ</th>
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

        function showEditForm(orphanID, studentID, statusOrder, statusStart, statusEnd, notes) {
            document.getElementById('editModal').style.display = 'block';

            document.getElementById('editOrphanID').value = orphanID;
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

        function addOrphan() {
            const formData = $('#addOrphanForm').serialize();

            $.ajax({
                url: 'Controllers/orphans_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadOrphans();
                        $('#addOrphanForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateOrphan() {
            const formData = $('#editOrphanForm').serialize();

            $.ajax({
                url: 'Controllers/orphans_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('editModal');
                        loadOrphans();
                    } else {
                        alert(result.error || 'Ошибка при обновлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteOrphan(orphanID) {
            if (confirm('Вы уверены, что хотите удалить эту запись?')) {
                $.ajax({
                    url: 'Controllers/orphans_controller.php',
                    type: 'GET',
                    data: { delete_id: orphanID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadOrphans();
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

        function loadOrphans() {
            $.ajax({
                url: 'Controllers/orphans_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(orphan) {
                            tableBody += `
                                <tr>
                                    <td>${orphan.OrphanID || ''}</td>
                                    <td>${orphan.LastName || ''} ${orphan.FirstName || ''} ${orphan.MiddleName || ''} (ID: ${orphan.StudentID || ''})</td>
                                    <td>${orphan.StatusOrder || ''}</td>
                                    <td>${orphan.StatusStart || ''}</td>
                                    <td>${orphan.StatusEnd || ''}</td>
                                    <td>${orphan.Notes || ''}</td>
                                    <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${orphan.OrphanID},
                                            ${orphan.StudentID},
                                            '${escapeSingleQuote(orphan.StatusOrder)}',
                                            '${orphan.StatusStart}',
                                            '${orphan.StatusEnd}',
                                            '${escapeSingleQuote(orphan.Notes)}'
                                        )" class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php
require_once "auth_check.php";
 endif; ?>
                                </tr>
                            `;
                        });
                        $('#orphansTable tbody').html(tableBody);
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
            if (str === null || str === undefined) return '';
            return String(str).replace(/'/g, "\\'");
        }

        $(document).ready(function() {
            loadOrphans();
        });
    </script>
</body>
</html>
