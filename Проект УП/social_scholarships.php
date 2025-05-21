<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Социальная стипендия</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Социальная стипендия</h1>
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
            <li><a href="social_scholarships.php" class="active">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Социальные стипендии</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить запись</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента">

                <label for="documentBasis">Документ основание:</label>
                <input type="text" id="documentBasis" name="documentBasis" placeholder="Введите документ">

                <label for="paymentStart">Начало выплаты:</label>
                <input type="date" id="paymentStart" name="paymentStart">

                <label for="paymentEnd">Конец выплаты:</label>
                <input type="date" id="paymentEnd" name="paymentEnd">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание">

                <button type="button" onclick="loadScholarships()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Форма добавления -->
            <div id="addForm" style="display: none;">
                <h3>Добавить запись о социальной стипендии</h3>
                <form id="addScholarshipForm">
                    <input type="hidden" name="add_scholarship" value="1">

                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>

                    <label for="addDocumentBasis">Документ основание:</label>
                    <input type="text" id="addDocumentBasis" name="DocumentBasis">

                    <label for="addPaymentStart">Начало выплаты:</label>
                    <input type="date" id="addPaymentStart" name="PaymentStart" required>

                    <label for="addPaymentEnd">Конец выплаты:</label>
                    <input type="date" id="addPaymentEnd" name="PaymentEnd">

                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">

                    <button type="button" onclick="addScholarship()" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма редактирования -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать запись о социальной стипендии</h3>
                <form id="editScholarshipForm">
                    <input type="hidden" name="edit_scholarship" value="1">
                    <input type="hidden" id="editScholarshipID" name="ScholarshipID">

                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>

                    <label for="editDocumentBasis">Документ основание:</label>
                    <input type="text" id="editDocumentBasis" name="DocumentBasis">

                    <label for="editPaymentStart">Начало выплаты:</label>
                    <input type="date" id="editPaymentStart" name="PaymentStart" required>

                    <label for="editPaymentEnd">Конец выплаты:</label>
                    <input type="date" id="editPaymentEnd" name="PaymentEnd">

                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">

                    <button type="button" onclick="updateScholarship()" class="button button-blue">Сохранить</button>
                </form>
            </div>

            <!-- Таблица с данными -->
            <table id="scholarshipsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Студент</th>
                        <th>Документ основание</th>
                        <th>Начало выплаты</th>
                        <th>Конец выплаты</th>
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

        function showEditForm(scholarshipID, studentID, documentBasis, paymentStart, paymentEnd, notes) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('addForm').style.display = 'none';

            document.getElementById('editScholarshipID').value = scholarshipID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editDocumentBasis').value = documentBasis;
            document.getElementById('editPaymentStart').value = paymentStart;
            document.getElementById('editPaymentEnd').value = paymentEnd;
            document.getElementById('editNotes').value = notes;
        }

        function addScholarship() {
            const formData = $('#addScholarshipForm').serialize();

            $.ajax({
                url: 'Controllers/social_scholarships_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadScholarships();
                        document.getElementById('addForm').style.display = 'none';
                        $('#addScholarshipForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateScholarship() {
            const formData = $('#editScholarshipForm').serialize();

            $.ajax({
                url: 'Controllers/social_scholarships_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadScholarships();
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

        function loadScholarships() {
            $.ajax({
                url: 'Controllers/social_scholarships_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(scholarship) {
                            tableBody += `
                                <tr>
                                    <td>${scholarship.ScholarshipID || ''}</td>
                                    <td>${scholarship.LastName || ''} ${scholarship.FirstName || ''} ${scholarship.MiddleName || ''} (ID: ${scholarship.StudentID || ''})</td>
                                    <td>${scholarship.DocumentBasis || ''}</td>
                                    <td>${scholarship.PaymentStart || ''}</td>
                                    <td>${scholarship.PaymentEnd || ''}</td>
                                    <td>${scholarship.Notes || ''}</td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${scholarship.ScholarshipID},
                                            ${scholarship.StudentID},
                                            '${escapeSingleQuote(scholarship.DocumentBasis)}',
                                            '${scholarship.PaymentStart}',
                                            '${scholarship.PaymentEnd}',
                                            '${escapeSingleQuote(scholarship.Notes)}'
                                        )" class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            `;
                        });
                        $('#scholarshipsTable tbody').html(tableBody);
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
            loadScholarships();
        });
    </script>
</body>
</html>