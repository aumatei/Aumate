<?php
session_start();
require_once('db.php');

// Проверка авторизации админа
if (!isset($_SESSION['admin'])) {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        if ($_POST['login'] === 'admin' && $_POST['password'] === 'bookworm') {
            $_SESSION['admin'] = true;
        } else {
            $_SESSION['error'] = "Неверный логин или пароль";
            header("Location: admin.php");
            exit();
        }
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <!-- Заголовок страницы входа для админа -->
            <title>Админ-панель - Буквоежка</title>
            <!-- Подключение основного файла стилей -->
            <link rel="stylesheet" href="./css/style.css">
            <meta name="viewport" content="width=device-width, initial-scale=1">
        </head>
        <body>
            <!-- Контейнер для центрирования и отступов -->
            <div class="container">
                <!-- Блок формы входа для админа -->
                <div class="form-container">
                    <h1>Вход в панель администратора</h1>
                    <?php if(isset($_SESSION['error'])): ?>
                        <!-- Сообщение об ошибке -->
                        <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <!-- Форма входа -->
                    <form method="post">
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
                        <a href="index.php">На главную</a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

// Получаем карточки на модерации
$stmt = $conn->prepare("
    SELECT c.*, u.fullname, u.phone, u.email
    FROM cards c
    JOIN users u ON c.user_id = u.id
    WHERE c.status = 'На рассмотрении'
    ORDER BY c.created_at DESC
");
$stmt->execute();
$pending_cards = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Заголовок админ-панели -->
    <title>Админ-панель - Буквоежка</title>
    <!-- Подключение основного файла стилей -->
    <link rel="stylesheet" href="./css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <!-- Контейнер для центрирования и отступов -->
    <div class="container">
        <h1>Панель администратора</h1>

        <?php if(isset($_SESSION['success'])): ?>
            <!-- Сообщение об успехе -->
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Заголовок секции карточек на модерации -->
        <h2 class="subtitle">Карточки на модерации</h2>
        <!-- Список карточек на модерации -->
        <div class="card-list">
            <?php if($pending_cards->num_rows > 0): ?>
                <?php while($card = $pending_cards->fetch_assoc()): ?>
                    <!-- Карточка для модерации -->
                    <div class="card">
                        <h3><?php echo htmlspecialchars($card['title']); ?></h3>
                        <p><strong>Автор:</strong> <?php echo htmlspecialchars($card['author']); ?></p>
                        <p><strong>Тип:</strong> <?php echo $card['type'] === 'share' ? 'Готов поделиться' : 'Хочу в библиотеку'; ?></p>
                        <?php if($card['publisher']): ?>
                            <p><strong>Издательство:</strong> <?php echo htmlspecialchars($card['publisher']); ?></p>
                        <?php endif; ?>
                        <?php if($card['year_published']): ?>
                            <p><strong>Год издания:</strong> <?php echo htmlspecialchars($card['year_published']); ?></p>
                        <?php endif; ?>
                        <?php if($card['binding']): ?>
                            <p><strong>Переплет:</strong> <?php echo htmlspecialchars($card['binding']); ?></p>
                        <?php endif; ?>
                        <?php if($card['condition_desc']): ?>
                            <p><strong>Состояние:</strong> <?php echo htmlspecialchars($card['condition_desc']); ?></p>
                        <?php endif; ?>

                        <p><strong>Пользователь:</strong> <?php echo htmlspecialchars($card['fullname']); ?></p>
                        <p><strong>Телефон:</strong> <?php echo htmlspecialchars($card['phone']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($card['email']); ?></p>

                        <!-- Блок действий модерации -->
                        <div class="actions" style="margin-top: 20px;">
                            <form action="moderate_card.php" method="post" style="width: 100%;">
                                <input type="hidden" name="card_id" value="<?php echo $card['id']; ?>">
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <textarea name="rejection_reason" placeholder="Причина отклонения (если отклоняете)" rows="2"></textarea>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <button type="submit" name="action" value="approve" class="btn" style="background-color: #27ae60;">Опубликовать</button>
                                    <button type="submit" name="action" value="reject" class="btn" style="background-color: #e74c3c;">Отклонить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Нет карточек на модерации</p>
            <?php endif; ?>
        </div>

        <!-- Навигация админ-панели -->
        <div class="nav">
            <a href="admin_logout.php">Выйти</a>
        </div>
    </div>
</body>
</html>