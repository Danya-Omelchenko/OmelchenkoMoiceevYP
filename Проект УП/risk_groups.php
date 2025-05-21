<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Группа риска</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Группа риска</h1>
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
            <li><a href="risk_groups.php" class="active">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Группа риска</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить запись</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <label for="studentID">ID студента:</label>
                <input type="text" id="studentID" name="studentID" placeholder="Введите ID студента">

                <label for="type">Тип:</label>
                <input type="text" id="type" name="type" placeholder="Введите тип">

                <label for="registrationDate">Дата постановки:</label>
                <input type="date" id="registrationDate" name="registrationDate">

                <label for="registrationReason">Основание постановки:</label>
                <input type="text" id="registrationReason" name="registrationReason" placeholder="Введите основание">

                <label for="removalDate">Дата снятия:</label>
                <input type="date" id="removalDate" name="removalDate">

                <label for="removalReason">Основание снятия:</label>
                <input type="text" id="removalReason" name="removalReason" placeholder="Введите основание">

                <label for="notes">Примечание:</label>
                <input type="text" id="notes" name="notes" placeholder="Введите примечание">

                <button type="button" onclick="loadRiskGroups()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Форма добавления -->
            <div id="addForm" style="display: none;">
                <h3>Добавить запись в группу риска</h3>
                <form id="addRiskGroupForm">
                    <input type="hidden" name="add_risk_group" value="1">

                    <label for="addStudentID">ID студента:</label>
                    <input type="number" id="addStudentID" name="StudentID" required>

                    <label for="addType">Тип:</label>
                    <input type="text" id="addType" name="Type" required>

                    <label for="addRegistrationDate">Дата постановки:</label>
                    <input type="date" id="addRegistrationDate" name="RegistrationDate" required>

                    <label for="addRegistrationReason">Основание постановки:</label>
                    <input type="text" id="addRegistrationReason" name="RegistrationReason">

                    <label for="addRemovalDate">Дата снятия:</label>
                    <input type="date" id="addRemovalDate" name="RemovalDate">

                    <label for="addRemovalReason">Основание снятия:</label>
                    <input type="text" id="addRemovalReason" name="RemovalReason">

                    <label for="addNotes">Примечание:</label>
                    <input type="text" id="addNotes" name="Notes">

                    <button type="button" onclick="addRiskGroup()" class="button button-blue">Добавить</button>
                </form>
            </div>

            <!-- Форма редактирования -->
            <div id="editForm" style="display: none;">
                <h3>Редактировать запись</h3>
                <form id="editRiskGroupForm">
                    <input type="hidden" name="edit_risk_group" value="1">
                    <input type="hidden" id="editRiskGroupID" name="RiskGroupID">

                    <label for="editStudentID">ID студента:</label>
                    <input type="number" id="editStudentID" name="StudentID" required>

                    <label for="editType">Тип:</label>
                    <input type="text" id="editType" name="Type" required>

                    <label for="editRegistrationDate">Дата постановки:</label>
                    <input type="date" id="editRegistrationDate" name="RegistrationDate" required>

                    <label for="editRegistrationReason">Основание постановки:</label>
                    <input type="text" id="editRegistrationReason" name="RegistrationReason">

                    <label for="editRemovalDate">Дата снятия:</label>
                    <input type="date" id="editRemovalDate" name="RemovalDate">

                    <label for="editRemovalReason">Основание снятия:</label>
                    <input type="text" id="editRemovalReason" name="RemovalReason">

                    <label for="editNotes">Примечание:</label>
                    <input type="text" id="editNotes" name="Notes">

                    <button type="button" onclick="updateRiskGroup()" class="button button-blue">Сохранить</button>
                </form>
            </div>

            <!-- Таблица с данными -->
            <table id="riskGroupsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Студент</th>
                        <th>Тип</th>
                        <th>Дата постановки</th>
                        <th>Основание постановки</th>
                        <th>Дата снятия</th>
                        <th>Основание снятия</th>
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

        function showEditForm(riskGroupID, studentID, type, registrationDate, registrationReason, removalDate, removalReason, notes) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('addForm').style.display = 'none';

            document.getElementById('editRiskGroupID').value = riskGroupID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editType').value = type;
            document.getElementById('editRegistrationDate').value = registrationDate;
            document.getElementById('editRegistrationReason').value = registrationReason || '';
            document.getElementById('editRemovalDate').value = removalDate || '';
            document.getElementById('editRemovalReason').value = removalReason || '';
            document.getElementById('editNotes').value = notes || '';
        }

        function addRiskGroup() {
            const formData = $('#addRiskGroupForm').serialize();

            $.ajax({
                url: 'Controllers/risk_group_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadRiskGroups();
                        document.getElementById('addForm').style.display = 'none';
                        $('#addRiskGroupForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении записи');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateRiskGroup() {
            const formData = $('#editRiskGroupForm').serialize();

            $.ajax({
                url: 'Controllers/risk_group_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadRiskGroups();
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

        function deleteRiskGroup(riskGroupID) {
            if (confirm('Вы уверены, что хотите удалить эту запись?')) {
                $.ajax({
                    url: 'Controllers/risk_group_controller.php',
                    type: 'GET',
                    data: { delete_id: riskGroupID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadRiskGroups();
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

        function loadRiskGroups() {
            $.ajax({
                url: 'Controllers/risk_group_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(group) {
                            tableBody += `
                                <tr>
                                    <td>${group.RiskGroupID || ''}</td>
                                    <td>${group.LastName || ''} ${group.FirstName || ''} ${group.MiddleName || ''} (ID: ${group.StudentID || ''})</td>
                                    <td>${group.Type || ''}</td>
                                    <td>${group.RegistrationDate || ''}</td>
                                    <td>${group.RegistrationReason || ''}</td>
                                    <td>${group.RemovalDate || ''}</td>
                                    <td>${group.RemovalReason || ''}</td>
                                    <td>${group.Notes || ''}</td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${group.RiskGroupID},
                                            ${group.StudentID},
                                            '${escapeSingleQuote(group.Type)}',
                                            '${group.RegistrationDate}',
                                            '${escapeSingleQuote(group.RegistrationReason)}',
                                            '${group.RemovalDate}',
                                            '${escapeSingleQuote(group.RemovalReason)}',
                                            '${escapeSingleQuote(group.Notes)}'
                                        )" class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            `;
                        });
                        $('#riskGroupsTable tbody').html(tableBody);
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
            loadRiskGroups();
        });
    </script>
</body>
</html>