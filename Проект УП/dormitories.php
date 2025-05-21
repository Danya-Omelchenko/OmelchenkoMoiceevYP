<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Общежитие</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Общежитие</h1>
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
            <li><a href="dormitories.php" class="active">Общежитие</a></li>
            <li><a href="risk_groups.php">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Студенты в общежитии</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить запись</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента">

                <label for="roomNumber">Номер комнаты:</label>
                <input type="text" id="roomNumber" name="roomNumber" placeholder="Введите номер комнаты">

                <label for="checkInDate">Дата заселения:</label>
                <input type="date" id="checkInDate" name="checkInDate">

                <label for="checkOutDate">Дата выселения:</label>
                <input type="date" id="checkOutDate" name="checkOutDate">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание">

                <button type="button" onclick="loadDormitories()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Форма добавления -->
            <div id="addForm" style="display: none;">
                <h3>Добавить запись о заселении</h3>
                <form id="addDormitoryForm">
                    <input type="hidden" name="add_dormitory" value="1">

                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>

                    <label for="addRoomNumber">Номер комнаты:</label>
                    <input type="text" id="addRoomNumber" name="RoomNumber" required>

                    <label for="addCheckInDate">Дата заселения:</label>
                    <input type="date" id="addCheckInDate" name="CheckInDate" required>

                    <label for="addCheckOutDate">Дата выселения:</label>
                    <input type="date" id="addCheckOutDate" name="CheckOutDate">

                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">

                    <button type="button" onclick="addDormitory()" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма редактирования -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать запись о заселении</h3>
                <form id="editDormitoryForm">
                    <input type="hidden" name="edit_dormitory" value="1">
                    <input type="hidden" id="editDormitoryID" name="DormitoryID">

                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>

                    <label for="editRoomNumber">Номер комнаты:</label>
                    <input type="text" id="editRoomNumber" name="RoomNumber" required>

                    <label for="editCheckInDate">Дата заселения:</label>
                    <input type="date" id="editCheckInDate" name="CheckInDate" required>

                    <label for="editCheckOutDate">Дата выселения:</label>
                    <input type="date" id="editCheckOutDate" name="CheckOutDate">

                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">

                    <button type="button" onclick="updateDormitory()" class="button button-blue">Сохранить</button>
                </form>
            </div>

            <!-- Таблица с данными -->
            <table id="dormitoriesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Студент</th>
                        <th>Номер комнаты</th>
                        <th>Дата заселения</th>
                        <th>Дата выселения</th>
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

        function showEditForm(dormitoryID, studentID, roomNumber, checkInDate, checkOutDate, notes) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('addForm').style.display = 'none';

            document.getElementById('editDormitoryID').value = dormitoryID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editRoomNumber').value = roomNumber;
            document.getElementById('editCheckInDate').value = checkInDate;
            document.getElementById('editCheckOutDate').value = checkOutDate;
            document.getElementById('editNotes').value = notes;
        }

        function addDormitory() {
            const formData = $('#addDormitoryForm').serialize();

            $.ajax({
                url: 'Controllers/dormitories_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadDormitories();
                        document.getElementById('addForm').style.display = 'none';
                        $('#addDormitoryForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateDormitory() {
            const formData = $('#editDormitoryForm').serialize();

            $.ajax({
                url: 'Controllers/dormitories_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadDormitories();
                        document.getElementById('editForm').style.display = 'none';
                        $('#addDormitoryForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при обновлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteDormitory(dormitoryID) {
            if (confirm('Вы уверены, что хотите удалить эту запись?')) {
                $.ajax({
                    url: 'Controllers/dormitories_controller.php',
                    type: 'GET',
                    data: { delete_id: dormitoryID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadDormitories();
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

        function loadDormitories() {
            $.ajax({
                url: 'Controllers/dormitories_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(dormitory) {
                            tableBody += `
                                <tr>
                                    <td>${dormitory.DormitoryID || ''}</td>
                                    <td>${dormitory.LastName || ''} ${dormitory.FirstName || ''} ${dormitory.MiddleName || ''} (ID: ${dormitory.StudentID || ''})</td>
                                    <td>${dormitory.RoomNumber || ''}</td>
                                    <td>${dormitory.CheckInDate || ''}</td>
                                    <td>${dormitory.CheckOutDate || ''}</td>
                                    <td>${dormitory.Notes || ''}</td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${dormitory.DormitoryID},
                                            ${dormitory.StudentID},
                                            '${escapeSingleQuote(dormitory.RoomNumber)}',
                                            '${dormitory.CheckInDate}',
                                            '${dormitory.CheckOutDate}',
                                            '${escapeSingleQuote(dormitory.Notes)}'
                                        )" class="button button-blue">Редактировать</button>                                    </td>
                                    <?php endif; ?>
                                </tr>
                            `;
                        });
                        $('#dormitoriesTable tbody').html(tableBody);
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
            loadDormitories();
        });
    </script>
</body>
</html>