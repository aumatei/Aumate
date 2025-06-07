<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Серверная валидация
    if (mb_strlen($login) < 6 || !preg_match('/^[А-Яа-яЁё]+$/u', $login)) {
        $_SESSION['error'] = "Логин должен содержать минимум 6 символов кириллицы";
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Пароль должен содержать минимум 6 символов";
        header("Location: register.php");
        exit();
    }

    if (!preg_match('/^[А-Яа-яЁё\s]+$/u', $fullname)) {
        $_SESSION['error'] = "ФИО должно содержать только кириллицу и пробелы";
        header("Location: register.php");
        exit();
    }

    if (!preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
        $_SESSION['error'] = "Неверный формат телефона";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Неверный формат email";
        header("Location: register.php");
        exit();
    }

    // Проверка уникальности логина
    $stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Пользователь с таким логином уже существует";
        header("Location: register.php");
        exit();
    }

    // Хеширование пароля и сохранение пользователя
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (login, password, fullname, phone, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $login, $hashed_password, $fullname, $phone, $email);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Регистрация успешна! Войдите в систему.";
        header("Location: index.php");
    } else {
        $_SESSION['error'] = "Ошибка при регистрации: " . $conn->error;
        header("Location: register.php");
    }
    exit();
}
?>