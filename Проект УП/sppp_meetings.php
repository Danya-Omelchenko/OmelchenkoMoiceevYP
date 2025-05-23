<?php
require_once "auth_check.php";


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заседания СППП</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Заседания СППП</h1>
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
            <li><a href="sppp_meetings.php" class="active">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Заседания СППП</h2>

            <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить заседание</button>
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
                    <label for="meetingDate">Дата заседания:</label>
                    <input type="date" id="meetingDate" name="meetingDate">
                </div>

                <div class="form-row">
                    <label for="callReason">Основание вызова:</label>
                    <input type="text" id="callReason" name="callReason" placeholder="Введите основание">
                </div>

                <div class="form-row">
                    <label for="presentEmployees">Присутствовали сотрудники:</label>
                    <input type="text" id="presentEmployees" name="presentEmployees" placeholder="Введите сотрудников">
                </div>

                <div class="form-row">
                    <label for="presentRepresentatives">Присутствовали представители:</label>
                    <input type="text" id="presentRepresentatives" name="presentRepresentatives" placeholder="Введите представителей">
                </div>

                <div class="form-row">
                    <label for="callCause">Причина вызова:</label>
                    <input type="text" id="callCause" name="callCause" placeholder="Введите причину">
                </div>

                <div class="form-row">
                    <label for="decision">Решение:</label>
                    <input type="text" id="decision" name="decision" placeholder="Введите решение">
                </div>

                <div class="form-row">
                    <label for="notes">Примечание:</label>
                    <input type="text" id="notes" name="notes" placeholder="Введите примечание">
                </div>

                <button type="button" onclick="loadMeetings()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления заседания -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить заседание СППП</h3>
                    <form id="addMeetingForm">
                        <input type="hidden" name="add_meeting" value="1">

                        <div class="form-row">
                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>
                </div>

                        <div class="form-row">
                    <label for="addMeetingDate">Дата заседания:</label>
                    <input type="date" id="addMeetingDate" name="MeetingDate" required>
                </div>

                        <div class="form-row">
                    <label for="addCallReason">Основание вызова:</label>
                    <input type="text" id="addCallReason" name="CallReason" required>
                </div>

                        <div class="form-row">
                    <label for="addPresentEmployees">Присутствовали сотрудники:</label>
                    <input type="text" id="addPresentEmployees" name="PresentEmployees">
                </div>

                        <div class="form-row">
                    <label for="addPresentRepresentatives">Присутствовали представители:</label>
                    <input type="text" id="addPresentRepresentatives" name="PresentRepresentatives">
                </div>

                        <div class="form-row">
                    <label for="addCallCause">Причина вызова:</label>
                    <input type="text" id="addCallCause" name="CallCause">
                </div>

                        <div class="form-row">
                    <label for="addDecision">Решение:</label>
                    <input type="text" id="addDecision" name="Decision">
                </div>

                        <div class="form-row">
                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">
                </div>

                        <button type="button" onclick="addMeeting()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования заседания -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать заседание СППП</h3>
                    <form id="editMeetingForm">
                        <input type="hidden" name="edit_meeting" value="1">
                        <input type="hidden" id="editMeetingID" name="MeetingID">

                        <div class="form-row">
                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>
                </div>

                        <div class="form-row">
                    <label for="editMeetingDate">Дата заседания:</label>
                    <input type="date" id="editMeetingDate" name="MeetingDate" required>
                </div>

                        <div class="form-row">
                    <label for="editCallReason">Основание вызова:</label>
                    <input type="text" id="editCallReason" name="CallReason" required>
                </div>

                        <div class="form-row">
                    <label for="editPresentEmployees">Присутствовали сотрудники:</label>
                    <input type="text" id="editPresentEmployees" name="PresentEmployees">
                </div>

                        <div class="form-row">
                    <label for="editPresentRepresentatives">Присутствовали представители:</label>
                    <input type="text" id="editPresentRepresentatives" name="PresentRepresentatives">
                </div>

                        <div class="form-row">
                    <label for="editCallCause">Причина вызова:</label>
                    <input type="text" id="editCallCause" name="CallCause">
                </div>

                        <div class="form-row">
                    <label for="editDecision">Решение:</label>
                    <input type="text" id="editDecision" name="Decision">
                </div>

                        <div class="form-row">
                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">
                </div>

                        <button type="button" onclick="updateMeeting()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Таблица с данными -->
            <table id="meetingsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Студент</th>
                        <th>Дата заседания</th>
                        <th>Основание вызова</th>
                        <th>Сотрудники</th>
                        <th>Представители</th>
                        <th>Причина</th>
                        <th>Решение</th>
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

        function showEditForm(meetingID, studentID, meetingDate, callReason, presentEmployees, presentRepresentatives, callCause, decision, notes) {
            document.getElementById('editModal').style.display = 'block';

            document.getElementById('editMeetingID').value = meetingID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editMeetingDate').value = meetingDate;
            document.getElementById('editCallReason').value = callReason;
            document.getElementById('editPresentEmployees').value = presentEmployees;
            document.getElementById('editPresentRepresentatives').value = presentRepresentatives;
            document.getElementById('editCallCause').value = callCause;
            document.getElementById('editDecision').value = decision;
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

        function addMeeting() {
            const formData = $('#addMeetingForm').serialize();

            $.ajax({
                url: 'Controllers/sppp_meetings_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadMeetings();
                        $('#addMeetingForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении заседания');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateMeeting() {
            const formData = $('#editMeetingForm').serialize();

            $.ajax({
                url: 'Controllers/sppp_meetings_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('editModal');
                        loadMeetings();
                    } else {
                        alert(result.error || 'Ошибка при обновлении заседания');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteMeeting(meetingID) {
            if (confirm('Вы уверены, что хотите удалить это заседание СППП?')) {
                $.ajax({
                    url: 'Controllers/sppp_meetings_controller.php',
                    type: 'GET',
                    data: { delete_id: meetingID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadMeetings();
                        } else {
                            alert(result.error || 'Ошибка при удалении заседания');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Ошибка: ' + error);
                    }
                });
            }
        }

        function loadMeetings() {
            $.ajax({
                url: 'Controllers/sppp_meetings_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(meeting) {
                            tableBody += `
                                <tr>
                                    <td>${meeting.MeetingID || ''}</td>
                                    <td>${meeting.LastName || ''} ${meeting.FirstName || ''} ${meeting.MiddleName || ''} (ID: ${meeting.StudentID || ''})</td>
                                    <td>${meeting.MeetingDate || ''}</td>
                                    <td>${meeting.CallReason || ''}</td>
                                    <td>${meeting.PresentEmployees || ''}</td>
                                    <td>${meeting.PresentRepresentatives || ''}</td>
                                    <td>${meeting.CallCause || ''}</td>
                                    <td>${meeting.Decision || ''}</td>
                                    <td>${meeting.Notes || ''}</td>
                                    <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${meeting.MeetingID},
                                            ${meeting.StudentID},
                                            '${meeting.MeetingDate}',
                                            '${escapeSingleQuote(meeting.CallReason)}',
                                            '${escapeSingleQuote(meeting.PresentEmployees)}',
                                            '${escapeSingleQuote(meeting.PresentRepresentatives)}',
                                            '${escapeSingleQuote(meeting.CallCause)}',
                                            '${escapeSingleQuote(meeting.Decision)}',
                                            '${escapeSingleQuote(meeting.Notes)}'
                                        )" class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php
                                    require_once "auth_check.php";
                                    endif; ?>
                                </tr>
                            `;
                        });
                        $('#meetingsTable tbody').html(tableBody);
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
            loadMeetings();
        });
    </script>
</body>
</html>
