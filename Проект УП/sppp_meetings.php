<?php
session_start();
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
            <li><a href="sppp_meetings.php" class="active">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Заседания СППП</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить заседание</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента">

                <label for="meetingDate">Дата заседания:</label>
                <input type="date" id="meetingDate" name="meetingDate">

                <label for="callReason">Основание вызова:</label>
                <input type="text" id="callReason" name="callReason" placeholder="Введите основание">

                <label for="presentEmployees">Присутствовали сотрудники:</label>
                <input type="text" id="presentEmployees" name="presentEmployees" placeholder="Введите сотрудников">

                <label for="presentRepresentatives">Присутствовали представители:</label>
                <input type="text" id="presentRepresentatives" name="presentRepresentatives" placeholder="Введите представителей">

                <label for="callCause">Причина вызова:</label>
                <input type="text" id="callCause" name="callCause" placeholder="Введите причину">

                <label for="decision">Решение:</label>
                <input type="text" id="decision" name="decision" placeholder="Введите решение">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание">

                <button type="button" onclick="loadMeetings()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Форма добавления -->
            <div id="addForm" style="display: none;">
                <h3>Добавить заседание СППП</h3>
                <form id="addMeetingForm">
                    <input type="hidden" name="add_meeting" value="1">

                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>

                    <label for="addMeetingDate">Дата заседания:</label>
                    <input type="date" id="addMeetingDate" name="MeetingDate" required>

                    <label for="addCallReason">Основание вызова:</label>
                    <input type="text" id="addCallReason" name="CallReason" required>

                    <label for="addPresentEmployees">Присутствовали сотрудники:</label>
                    <input type="text" id="addPresentEmployees" name="PresentEmployees">

                    <label for="addPresentRepresentatives">Присутствовали представители:</label>
                    <input type="text" id="addPresentRepresentatives" name="PresentRepresentatives">

                    <label for="addCallCause">Причина вызова:</label>
                    <input type="text" id="addCallCause" name="CallCause">

                    <label for="addDecision">Решение:</label>
                    <input type="text" id="addDecision" name="Decision">

                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">

                    <button type="button" onclick="addMeeting()" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма редактирования -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать заседание СППП</h3>
                <form id="editMeetingForm">
                    <input type="hidden" name="edit_meeting" value="1">
                    <input type="hidden" id="editMeetingID" name="MeetingID">

                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>

                    <label for="editMeetingDate">Дата заседания:</label>
                    <input type="date" id="editMeetingDate" name="MeetingDate" required>

                    <label for="editCallReason">Основание вызова:</label>
                    <input type="text" id="editCallReason" name="CallReason" required>

                    <label for="editPresentEmployees">Присутствовали сотрудники:</label>
                    <input type="text" id="editPresentEmployees" name="PresentEmployees">

                    <label for="editPresentRepresentatives">Присутствовали представители:</label>
                    <input type="text" id="editPresentRepresentatives" name="PresentRepresentatives">

                    <label for="editCallCause">Причина вызова:</label>
                    <input type="text" id="editCallCause" name="CallCause">

                    <label for="editDecision">Решение:</label>
                    <input type="text" id="editDecision" name="Decision">

                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">

                    <button type="button" onclick="updateMeeting()" class="button button-blue">Сохранить</button>
                </form>
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
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <th>Действия</th>
                        <?php endif; ?>
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
            document.getElementById('addForm').style.display = 'block';
            document.getElementById('editForm').style.display = 'none';
        }

        function showEditForm(meetingID, studentID, meetingDate, callReason, presentEmployees, presentRepresentatives, callCause, decision, notes) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('addForm').style.display = 'none';

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

        function addMeeting() {
            const formData = $('#addMeetingForm').serialize();

            $.ajax({
                url: 'Controllers/sppp_meetings_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadMeetings();
                        document.getElementById('addForm').style.display = 'none';
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
                        loadMeetings();
                        document.getElementById('editForm').style.display = 'none';
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
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
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
                                    <?php endif; ?>
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
