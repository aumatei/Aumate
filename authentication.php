<!-- //admin_logout.php
//admin.php
//archive_card.php
//create_card.php
//db.php
//db.sql
//index.php
//login.php
//logout.php
//moderate_card.php
//my_cards.php
//register.php
//save_card.php
//save_register.php
//style.css -->

<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Заголовок страницы аутентификации -->
    <title>Вход - Буквоежка</title>
    <!-- Подключение основного файла стилей -->
    <link rel="stylesheet" href="./css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <!-- Контейнер для центрирования и отступов -->
    <div class="container">
        <!-- Блок формы аутентификации -->
        <div class="form-container">
            <h1>Буквоежка</h1>
            <p style="text-align: center; margin-bottom: 30px;">Система обмена книгами</p>

            <?php if(isset($_SESSION['error'])): ?>
                <!-- Сообщение об ошибке -->
                <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <!-- Сообщение об успехе -->
                <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <!-- Форма входа -->
            <form action="login.php" method="post">
                <div class="form-group">
                    <label>Логин:</label>
                    <input type="text" name="login" required>
                </div>

                <div class="form-group">
                    <label>Пароль:</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit">Войти</button>
            </form>

            <!-- Навигация под формой -->
            <div class="nav">
                <a href="register.php">Зарегистрироваться</a>
                <a href="admin.php">Панель администратора</a>
            </div>
        </div>
    </div>
</body>
</html>