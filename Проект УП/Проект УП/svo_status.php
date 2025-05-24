<?php
require_once "auth_check.php";


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>СВО</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Статус СВО</h1>
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
            <li><a href="orphans.php">Сироты</a></li>
            <li><a href="disabled.php">Инвалиды</a></li>
            <li><a href="special_needs.php">ОВЗ</a></li>
            <li><a href="dormitories.php">Общежитие</a></li>
            <li><a href="rooms.php">Комнаты</a></li>
            <li><a href="risk_groups.php">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php" class="active">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Статусы СВО студентов</h2>

            <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить статус СВО</button>
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
                    <label for="documentBasis">Документ основание:</label>
                    <input type="text" id="documentBasis" name="documentBasis" placeholder="Введите документ">
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

                <button type="button" onclick="loadSVOStatuses()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления статуса -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить статус СВО</h3>
                    <form id="addStatusForm">
                        <input type="hidden" name="add_status" value="1">

                        <div class="form-row">
                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>
                </div>

                        <div class="form-row">
                    <label for="addDocumentBasis">Документ основание:</label>
                    <input type="text" id="addDocumentBasis" name="DocumentBasis" required>
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

                        <button type="button" onclick="addStatus()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования статуса -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать статус СВО</h3>
                    <form id="editStatusForm">
                        <input type="hidden" name="edit_status" value="1">
                        <input type="hidden" id="editSVOStatusID" name="SVOStatusID">

                        <div class="form-row">
                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>
                </div>

                        <div class="form-row">
                    <label for="editDocumentBasis">Документ основание:</label>
                    <input type="text" id="editDocumentBasis" name="DocumentBasis" required>
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

                        <button type="button" onclick="updateStatus()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Таблица с данными -->
            <table id="statusTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Студент</th>
                        <th>Документ основание</th>
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

        function showEditForm(svoStatusID, studentID, documentBasis, statusStart, statusEnd, notes) {
            document.getElementById('editModal').style.display = 'block';

            document.getElementById('editSVOStatusID').value = svoStatusID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editDocumentBasis').value = documentBasis;
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

        function addStatus() {
            const formData = $('#addStatusForm').serialize();

            $.ajax({
                url: 'Controllers/svo_status_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadSVOStatuses();
                        $('#addStatusForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении статуса');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateStatus() {
            const formData = $('#editStatusForm').serialize();

            $.ajax({
                url: 'Controllers/svo_status_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('editModal');
                        loadSVOStatuses();
                    } else {
                        alert(result.error || 'Ошибка при обновлении статуса');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function loadSVOStatuses() {
            $.ajax({
                url: 'Controllers/svo_status_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        // Обновление таблицы с данными
                        let tableBody = '';
                        result.data.forEach(function(status) {
                            tableBody += `
                                <tr>
                                    <td>${status.SVOStatusID}</td>
                                    <td>${status.LastName} ${status.FirstName} ${status.MiddleName} (ID: ${status.StudentID})</td>
                                    <td>${status.DocumentBasis}</td>
                                    <td>${status.StatusStart}</td>
                                    <td>${status.StatusEnd}</td>
                                    <td>${status.Notes}</td>
                                    <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${status.SVOStatusID},
                                            ${status.StudentID},
                                            '${status.DocumentBasis.replace(/'/g, "\\'").replace(/"/g, '&quot;')}',
                                            '${status.StatusStart}',
                                            '${status.StatusEnd}',
                                            '${status.Notes ? status.Notes.replace(/'/g, "\\'").replace(/"/g, '&quot;') : ''}'
                                        )"
                                        class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php
require_once "auth_check.php";
 endif; ?>
                                </tr>
                            `;
                        });
                        $('#statusTable tbody').html(tableBody);
                    } else {
                        alert(result.error || 'Ошибка при загрузке данных');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        $(document).ready(function() {
            // Загрузка данных при старте страницы
            loadSVOStatuses();
        });
    </script>
</body>
</html>
