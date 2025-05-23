<?php
require_once "auth_check.php";


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
                    <label for="type">Тип:</label>
                    <input type="text" id="type" name="type" placeholder="Введите тип">
                </div>

                <div class="form-row">
                    <label for="registrationDate">Дата постановки:</label>
                    <input type="date" id="registrationDate" name="registrationDate">
                </div>

                <div class="form-row">
                    <label for="registrationReason">Основание постановки:</label>
                    <input type="text" id="registrationReason" name="registrationReason" placeholder="Введите основание">
                </div>

                <div class="form-row">
                    <label for="removalDate">Дата снятия:</label>
                    <input type="date" id="removalDate" name="removalDate">
                </div>

                <div class="form-row">
                    <label for="removalReason">Основание снятия:</label>
                    <input type="text" id="removalReason" name="removalReason" placeholder="Введите основание">
                </div>

                <div class="form-row">
                    <label for="notes">Примечание:</label>
                    <input type="text" id="notes" name="notes" placeholder="Введите примечание">
                </div>

                <button type="button" onclick="loadRiskGroups()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления записи -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить запись в группу риска</h3>
                    <form id="addRiskGroupForm">
                        <input type="hidden" name="add_risk_group" value="1">

                        <div class="form-row">
                            <label for="addStudentID">ID студента:</label>
                            <input type="number" id="addStudentID" name="StudentID" required>
                        </div>

                        <div class="form-row">
                            <label for="addType">Тип:</label>
                            <input type="text" id="addType" name="Type" required>
                        </div>

                        <div class="form-row">
                            <label for="addRegistrationDate">Дата постановки:</label>
                            <input type="date" id="addRegistrationDate" name="RegistrationDate" required>
                        </div>

                        <div class="form-row">
                            <label for="addRegistrationReason">Основание постановки:</label>
                            <input type="text" id="addRegistrationReason" name="RegistrationReason">
                        </div>

                        <div class="form-row">
                            <label for="addRemovalDate">Дата снятия:</label>
                            <input type="date" id="addRemovalDate" name="RemovalDate">
                        </div>

                        <div class="form-row">
                            <label for="addRemovalReason">Основание снятия:</label>
                            <input type="text" id="addRemovalReason" name="RemovalReason">
                        </div>

                        <div class="form-row">
                            <label for="addNotes">Примечание:</label>
                            <input type="text" id="addNotes" name="Notes">
                        </div>

                        <button type="button" onclick="addRiskGroup()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования записи -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
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

        function showEditForm(riskGroupID, studentID, type, registrationDate, registrationReason, removalDate, removalReason, notes) {
            document.getElementById('editModal').style.display = 'block';

            document.getElementById('editRiskGroupID').value = riskGroupID;
            document.getElementById('editStudentID').value = studentID;
            document.getElementById('editType').value = type;
            document.getElementById('editRegistrationDate').value = registrationDate;
            document.getElementById('editRegistrationReason').value = registrationReason || '';
            document.getElementById('editRemovalDate').value = removalDate || '';
            document.getElementById('editRemovalReason').value = removalReason || '';
            document.getElementById('editNotes').value = notes || '';
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

        function addRiskGroup() {
            const formData = $('#addRiskGroupForm').serialize();

            $.ajax({
                url: 'Controllers/risk_group_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadRiskGroups();
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
                        closeModal('editModal');
                        loadRiskGroups();
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
                                    <?php
require_once "auth_check.php";
 if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
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
                                        <button onclick="deleteRiskGroup(${group.RiskGroupID})" class="button button-red">Удалить</button>
                                    </td>
                                    <?php
require_once "auth_check.php";
 endif; ?>
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
