<?
session_start();
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
            <li><a href="svo_status.php" class="active">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Статусы СВО студентов</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить статус СВО</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm" method="get" action="svo_status.php">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента">

                <label for="documentBasis">Документ основание:</label>
                <input type="text" id="documentBasis" name="documentBasis" placeholder="Введите документ">

                <label for="statusStart">Начало статуса:</label>
                <input type="date" id="statusStart" name="statusStart">

                <label for="statusEnd">Конец статуса:</label>
                <input type="date" id="statusEnd" name="statusEnd">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание">

                <button type="submit" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Форма добавления -->
            <div id="addForm" style="display: none;">
                <h3>Добавить статус СВО</h3>
                <form id="addStatusForm">
                    <input type="hidden" name="add_status" value="1">

                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>

                    <label for="addDocumentBasis">Документ основание:</label>
                    <input type="text" id="addDocumentBasis" name="DocumentBasis" required>

                    <label for="addStatusStart">Начало статуса:</label>
                    <input type="date" id="addStatusStart" name="StatusStart" required>

                    <label for="addStatusEnd">Конец статуса:</label>
                    <input type="date" id="addStatusEnd" name="StatusEnd" required>

                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">

                    <button type="button" onclick="addStatus()" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма редактирования -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать статус СВО</h3>
                <form id="editStatusForm">
                    <input type="hidden" name="edit_status" value="1">
                    <input type="hidden" id="editSVOStatusID" name="SVOStatusID">

                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>

                    <label for="editDocumentBasis">Документ основание:</label>
                    <input type="text" id="editDocumentBasis" name="DocumentBasis" required>

                    <label for="editStatusStart">Начало статуса:</label>
                    <input type="date" id="editStatusStart" name="StatusStart" required>

                    <label for="editStatusEnd">Конец статуса:</label>
                    <input type="date" id="editStatusEnd" name="StatusEnd" required>

                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">

                    <button type="button" onclick="updateStatus()" class="button button-blue">Сохранить</button>
                </form>
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
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <th>Действия</th>
                        <?php endif; ?>
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
            document.getElementById('addForm').style.display = 'block';
            document.getElementById('editForm').style.display = 'none';
        }

        function showEditForm(svoStatusID, studentID, documentBasis, statusStart, statusEnd, notes) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('addForm').style.display = 'none';

            document.getElementById('editSVOStatusID').value = svoStatusID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editDocumentBasis').value = documentBasis;
            document.getElementById('editStatusStart').value = statusStart;
            document.getElementById('editStatusEnd').value = statusEnd;
            document.getElementById('editNotes').value = notes;
        }

        function addStatus() {
            const formData = $('#addStatusForm').serialize();

            $.ajax({
                url: 'controllers/svo_status_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        location.reload();
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
                url: 'controllers/svo_status_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        location.reload();
                    } else {
                        alert(result.error || 'Ошибка при обновлении статуса');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        $(document).ready(function() {
            // Загрузка данных при старте страницы
            $.ajax({
                url: 'controllers/svo_status_controller.php',
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
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${status.SVOStatusID},
                                            ${status.StudentID},
                                            '${status.DocumentBasis}',
                                            '${status.StatusStart}',
                                            '${status.StatusEnd}',
                                            '${status.Notes}'
                                        )"
                                        class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php endif; ?>
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
        });
    </script>
</body>
</html>
