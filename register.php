<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Заголовок страницы регистрации -->
    <title>Регистрация - Буквоежка</title>
    <!-- Подключение основного файла стилей -->
    <link rel="stylesheet" href="./css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <!-- Контейнер для центрирования и отступов -->
    <div class="container">
        <!-- Блок формы регистрации -->
        <div class="form-container">
            <h1>Регистрация в системе</h1>

            <?php if(isset($_SESSION['error'])): ?>
                <!-- Сообщение об ошибке -->
                <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <!-- Форма регистрации -->
            <form action="save_register.php" method="post" onsubmit="return validateForm()">

                <div class="form-group">
                    <label>ФИО:</label>
                    <input type="text" name="fullname" id="fullname" required pattern="[А-Яа-яЁё\s]+" title="Только кириллица и пробелы">
                </div>

                <div class="form-group">
                    <label>Телефон:</label>
                    <input type="tel" name="phone" id="phone" required pattern="\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}" title="Формат: +7(XXX)-XXX-XX-XX">
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label>Логин (минимум 6 символов, кириллица):</label>
                    <input type="text" name="login" id="login" required minlength="6" pattern="[А-Яа-яЁё]{6,}" title="Минимум 6 символов, только кириллица">
                </div>

                <div class="form-group">
                    <label>Пароль (минимум 6 символов):</label>
                    <input type="password" name="password" id="password" required minlength="6">
                </div>

                <!-- Блок для вывода ошибок валидации JS -->
                <div id="error" class="error" style="display: none;"></div>
                <button type="submit">Зарегистрироваться</button>
            </form>

            <!-- Навигация под формой -->
            <div class="nav">
                <a href="index.php">Уже есть аккаунт? Войти</a>
            </div>
        </div>
    </div>

    <!-- Скрипт для валидации формы и маски телефона -->
    <script>
    function validateForm() {
        const login = document.getElementById('login').value;
        const password = document.getElementById('password').value;
        const fullname = document.getElementById('fullname').value;
        const phone = document.getElementById('phone').value;
        const email = document.getElementById('email').value;
        const error = document.getElementById('error');

        // Проверка логина
        if (!/^[А-Яа-яЁё]{6,}$/.test(login)) {
            error.textContent = 'Логин должен содержать минимум 6 символов кириллицы';
            error.style.display = 'block';
            return false;
        }

        // Проверка пароля
        if (password.length < 6) {
            error.textContent = 'Пароль должен содержать минимум 6 символов';
            error.style.display = 'block';
            return false;
        }

        // Проверка ФИО
        if (!/^[А-Яа-яЁё\s]+$/.test(fullname)) {
            error.textContent = 'ФИО должно содержать только кириллицу и пробелы';
            error.style.display = 'block';
            return false;
        }

        // Проверка телефона
        if (!/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/.test(phone)) {
            error.textContent = 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX';
            error.style.display = 'block';
            return false;
        }

        // Проверка email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            error.textContent = 'Введите корректный email адрес';
            error.style.display = 'block';
            return false;
        }

        return true;
    }

    // Маска для телефона
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
        if (!x[2] && x[1]) {
            e.target.value = '+7(' + x[1];
        } else if (!x[3] && x[2]) {
            e.target.value = '+7(' + x[1] + ')-' + x[2];
        } else if (!x[4] && x[3]) {
            e.target.value = '+7(' + x[1] + ')-' + x[2] + '-' + x[3];
        } else if (x[4]) {
            e.target.value = '+7(' + x[1] + ')-' + x[2] + '-' + x[3] + '-' + x[4];
        }
    });
    </script>
</body>
</html>