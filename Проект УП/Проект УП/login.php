<?php
session_start();

// Если пользователь уже авторизован, перенаправляем на главную
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Простая проверка входа (замените на реальную аутентификацию)
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Здесь должна быть реальная проверка пользователя в базе данных
    if ($username === 'admin' && $password === 'password' && $role === 'admin') {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Перенаправляем на сохраненный URL или на главную
        $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
        unset($_SESSION['redirect_url']); // Удаляем сохраненный URL

        header('Location: ' . $redirect_url);
        exit;
    } elseif ($username === 's1' && $password === '123' && $role === 'employee') {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Перенаправляем на сохраненный URL или на главную
        $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
        unset($_SESSION['redirect_url']); // Удаляем сохраненный URL

        header('Location: ' . $redirect_url);
        exit;
    } else {
        $error = "Неверное имя пользователя или пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <img src="img/logo.png" alt="Логотип">
                <h1>Вход в систему</h1>
            </div>
        </div>
    </header>
    <main>
        <section>
            <form class="form1" action="login.php" method="post">
                <label for="username">Имя пользователя:</label>
                <input class="input" type="text" id="username" name="username" required>

                <label for="password">Пароль:</label>
                <input class="input" type="password" id="password" name="password" required>

                <label for="role">Роль:</label>
                <select class="select" id="role" name="role" required>
                    <option value="admin">Администратор</option>
                    <option value="employee">Сотрудник</option>
                </select>

                <button type="submit" class="button button-red1">Войти</button>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; Моисеев и Омельченко, 2025 Информационная система учебного заведения</p>
    </footer>
</body>
</html>
