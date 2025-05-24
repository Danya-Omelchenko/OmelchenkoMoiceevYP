<?php
require_once "auth_check.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отделения/Факультеты</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Отделения/Факультеты</h1>
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
            <li><a href="departments.php" class="active">Отделения</a></li>
            <li><a href="orphans.php">Сироты</a></li>
            <li><a href="disabled.php" class="active">Инвалиды</a></li>
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
            <h2>Управление отделениями</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить отделение</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <div class="form-row">
                    <label for="departmentName">Название отделения:</label>
                    <input type="text" id="departmentName" name="departmentName" placeholder="Введите название">
                </div>

                <button type="button" onclick="loadDepartments()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить отделение</h3>
                    <form id="addDepartmentForm">
                        <input type="hidden" name="add_department" value="1">

                        <div class="form-row">
                            <label for="addDepartmentName">Название отделения:</label>
                            <input type="text" id="addDepartmentName" name="DepartmentName" required>
                        </div>

                        <button type="button" onclick="addDepartment()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать отделение</h3>
                    <form id="editDepartmentForm">
                        <input type="hidden" name="edit_department" value="1">
                        <input type="hidden" id="editDepartmentID" name="DepartmentID">

                        <div class="form-row">
                            <label for="editDepartmentName">Название отделения:</label>
                            <input type="text" id="editDepartmentName" name="DepartmentName" required>
                        </div>

                        <button type="button" onclick="updateDepartment()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Таблица с данными -->
            <table id="departmentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название отделения</th>
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
            document.getElementById('addModal').style.display = 'block';
        }

        function showEditForm(departmentID, departmentName) {
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('editDepartmentID').value = departmentID;
            document.getElementById('editDepartmentName').value = departmentName;
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        };

        function addDepartment() {
            const formData = $('#addDepartmentForm').serialize();
            $.ajax({
                url: 'Controllers/department_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadDepartments();
                        $('#addDepartmentForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении отделения');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateDepartment() {
            const formData = $('#editDepartmentForm').serialize();
            $.ajax({
                url: 'Controllers/department_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('editModal');
                        loadDepartments();
                    } else {
                        alert(result.error || 'Ошибка при обновлении отделения');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteDepartment(departmentID) {
            if (confirm('Вы уверены, что хотите удалить это отделение?')) {
                $.ajax({
                    url: 'Controllers/department_controller.php',
                    type: 'GET',
                    data: { delete_id: departmentID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadDepartments();
                        } else {
                            alert(result.error || 'Ошибка при удалении отделения');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Ошибка: ' + error);
                    }
                });
            }
        }

        function loadDepartments() {
            $.ajax({
                url: 'Controllers/department_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(department) {
                            tableBody += `
                                <tr>
                                    <td>${department.DepartmentID || ''}</td>
                                    <td>${department.DepartmentName || ''}</td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${department.DepartmentID},
                                            '${escapeSingleQuote(department.DepartmentName)}'
                                        )" class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            `;
                        });
                        $('#departmentsTable tbody').html(tableBody);
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
            loadDepartments();
        });
    </script>
</body>
</html>