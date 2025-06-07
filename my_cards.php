<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Получаем активные карточки пользователя
$stmt = $conn->prepare("
    SELECT * FROM cards
    WHERE user_id = ? AND status != 'Архив'
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$active_cards = $stmt->get_result();

// Получаем архивные карточки пользователя
$stmt = $conn->prepare("
    SELECT * FROM cards
    WHERE user_id = ? AND status = 'Архив'
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$archived_cards = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Заголовок страницы "Мои карточки" -->
    <title>Мои карточки - Буквоежка</title>
    <!-- Подключение основного файла стилей -->
    <link rel="stylesheet" href="./css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <!-- Контейнер для центрирования и отступов -->
    <div class="container">
        <h1>Мои карточки</h1>

        <?php if(isset($_SESSION['success'])): ?>
            <!-- Сообщение об успехе -->
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Заголовок секции активных карточек -->
        <h2 class="subtitle">Активные карточки</h2>
        <!-- Список активных карточек -->
        <div class="card-list">
            <?php if($active_cards->num_rows > 0): ?>
                <?php while($card = $active_cards->fetch_assoc()): ?>
                    <!-- Активная карточка -->
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
                        <p><strong>Статус:</strong> <span class="status status-<?php echo strtolower(str_replace(' ', '', $card['status'])); ?>">
                            <?php echo $card['status']; ?>
                        </span></p>
                        <?php if($card['rejection_reason']): ?>
                            <p><strong>Причина отклонения:</strong> <?php echo htmlspecialchars($card['rejection_reason']); ?></p>
                        <?php endif; ?>

                        <!-- Действия с активной карточкой -->
                        <div class="actions">
                            <form action="archive_card.php" method="post" style="width: 100%;">
                                <input type="hidden" name="card_id" value="<?php echo $card['id']; ?>">
                                <button type="submit" class="btn">В архив</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>У вас пока нет активных карточек</p>
            <?php endif; ?>
        </div>

        <!-- Заголовок секции архива -->
        <h2 class="subtitle">Архив</h2>
        <!-- Список архивных карточек -->
        <div class="card-list">
            <?php if($archived_cards->num_rows > 0): ?>
                <?php while($card = $archived_cards->fetch_assoc()): ?>
                    <!-- Архивная карточка -->
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
                        <p><strong>Статус:</strong> <span class="status status-архив">Архив</span></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>В архиве пока нет карточек</p>
            <?php endif; ?>
        </div>

        <!-- Навигация страницы "Мои карточки" -->
        <div class="nav">
            <a href="create_card.php" class="btn">Создать карточку</a>
            <a href="logout.php">Выйти</a>
        </div>
    </div>
</body>
</html>