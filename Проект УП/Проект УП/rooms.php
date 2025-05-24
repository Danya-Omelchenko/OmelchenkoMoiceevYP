<?php
require_once "auth_check.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Комнаты общежития</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Комнаты общежития</h1>
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
            <li><a href="departments.php">Отделения</a></li>
            <li><a href="orphans.php">Сироты</a></li>
            <li><a href="disabled.php">Инвалиды</a></li>
            <li><a href="special_needs.php">ОВЗ</a></li>
            <li><a href="dormitories.php">Общежитие</a></li>
            <li><a href="rooms.php" class="active">Комнаты</a></li>
            <li><a href="risk_groups.php">Группа риска</a></li>
            <li><a href="sppp_meetings.php">СППП</a></li>
            <li><a href="svo_status.php">СВО</a></li>
            <li><a href="social_scholarships.php">Социальная стипендия</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Комнаты общежития</h2>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button onclick="showAddForm()" class="button button-blue">Добавить комнату</button>
            <?php endif; ?>

            <!-- Форма фильтрации -->
            <form id="filterForm">
                <div class="form-row">
                    <label for="roomNumber">Номер комнаты:</label>
                    <input type="text" id="roomNumber" name="roomNumber" placeholder="Введите номер">
                </div>

                <div class="form-row">
                    <label for="capacity">Вместимость:</label>
                    <input type="number" id="capacity" name="capacity" placeholder="Введите вместимость">
                </div>

                <button type="button" onclick="loadRooms()" class="button button-blue">Применить фильтры</button>
            </form>

            <!-- Модальное окно для добавления -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('addModal')">&times;</span>
                    <h3>Добавить комнату</h3>
                    <form id="addRoomForm">
                        <input type="hidden" name="add_room" value="1">

                        <div class="form-row">
                            <label for="addRoomNumber">Номер комнаты:</label>
                            <input type="number" id="addRoomNumber" name="RoomNumber" required>
                        </div>

                        <div class="form-row">
                            <label for="addCapacity">Вместимость:</label>
                            <input type="number" id="addCapacity" name="Capacity" required>
                        </div>

                        <button type="button" onclick="addRoom()" class="button button-blue">Добавить</button>
                    </form>
                </div>
            </div>

            <!-- Модальное окно для редактирования -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
                    <h3>Редактировать комнату</h3>
                    <form id="editRoomForm">
                        <input type="hidden" name="edit_room" value="1">
                        <input type="hidden" id="editRoomID" name="RoomID">

                        <div class="form-row">
                            <label for="editRoomNumber">Номер комнаты:</label>
                            <input type="number" id="editRoomNumber" name="RoomNumber" required>
                        </div>

                        <div class="form-row">
                            <label for="editCapacity">Вместимость:</label>
                            <input type="number" id="editCapacity" name="Capacity" required>
                        </div>

                        <button type="button" onclick="updateRoom()" class="button button-blue">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Таблица с данными -->
            <table id="roomsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Номер комнаты</th>
                        <th>Вместимость</th>
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

        function showEditForm(roomID, roomNumber, capacity) {
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('editRoomID').value = roomID;
            document.getElementById('editRoomNumber').value = roomNumber;
            document.getElementById('editCapacity').value = capacity;
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        };

        function addRoom() {
            const formData = $('#addRoomForm').serialize();
            $.ajax({
                url: 'Controllers/room_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('addModal');
                        loadRooms();
                        $('#addRoomForm')[0].reset();
                    } else {
                        alert(result.error || 'Ошибка при добавлении комнаты');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function updateRoom() {
            const formData = $('#editRoomForm').serialize();
            $.ajax({
                url: 'Controllers/room_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closeModal('editModal');
                        loadRooms();
                    } else {
                        alert(result.error || 'Ошибка при обновлении комнаты');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }

        function deleteRoom(roomID) {
            if (confirm('Вы уверены, что хотите удалить эту комнату?')) {
                $.ajax({
                    url: 'Controllers/room_controller.php',
                    type: 'GET',
                    data: { delete_id: roomID },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            loadRooms();
                        } else {
                            alert(result.error || 'Ошибка при удалении комнаты');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Ошибка: ' + error);
                    }
                });
            }
        }

        function loadRooms() {
            $.ajax({
                url: 'Controllers/room_controller.php',
                type: 'GET',
                data: $('#filterForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let tableBody = '';
                        response.data.forEach(function(room) {
                            tableBody += `
                                <tr>
                                    <td>${room.RoomID || ''}</td>
                                    <td>${room.RoomNumber || ''}</td>
                                    <td>${room.Capacity || ''}</td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <button onclick="showEditForm(
                                            ${room.RoomID},
                                            ${room.RoomNumber},
                                            ${room.Capacity}
                                        )" class="button button-blue">Редактировать</button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            `;
                        });
                        $('#roomsTable tbody').html(tableBody);
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

        $(document).ready(function() {
            loadRooms();
        });
    </script>
</body>
</html>