<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Разрешён только POST-запрос");
}

function clean($str) {
    return trim(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}

$errors = [];

$full_name    = clean($_POST['full_name']    ?? '');
$phone        = clean($_POST['phone']        ?? '');
$email        = clean($_POST['email']        ?? '');
$section      = clean($_POST['section']      ?? '');
$birth_date   = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
$has_report   = (int)($_POST['has_report']   ?? 0);
$report_title = $has_report ? clean($_POST['report_title'] ?? '') : null;

// Валидация
if (!\( full_name || !preg_match('/^[А-Яа-яЁё\s\-]+ \)/u', $full_name)) {
    $errors[] = "Некорректное ФИО";
}
if (!\( phone || !preg_match('/^\+7\d{10} \)/', $phone)) {
    $errors[] = "Телефон должен быть в формате +7XXXXXXXXXX";
}
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный email";
}
if (!in_array($section, ['математика','физика','информатика'])) {
    $errors[] = "Выберите корректную секцию";
}
if ($has_report && empty($report_title)) {
    $errors[] = "Укажите название доклада";
}

if ($errors) {
    die(implode("<br>", $errors));
}

// Сохранение
try {
    $stmt = $pdo->prepare("
        INSERT INTO participants 
        (full_name, phone, email, section, birth_date, has_report, report_title)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $full_name,
        $phone,
        $email,
        $section,
        $birth_date,
        $has_report,
        $report_title
    ]);

    $id = $pdo->lastInsertId();

    // Показываем красивую страницу подтверждения
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
      <meta charset="UTF-8">
      <title>Регистрация завершена</title>
      <link rel="stylesheet" href="style.css">
      <style>
        .success { color: #15803d; text-align:center; margin: 2rem 0; }
        .info-table { margin: 2rem auto; max-width: 600px; }
        .info-table th { text-align: right; padding-right: 2rem; }
      </style>
    </head>
    <body>
    <div class="container">
      <h1 class="success">Регистрация успешно завершена!</h1>
      <table class="info-table">
        <tr><th>ФИО:</th><td><?= htmlspecialchars($full_name) ?></td></tr>
        <tr><th>Телефон:</th><td><?= htmlspecialchars($phone) ?></td></tr>
        <tr><th>Email:</th><td><?= htmlspecialchars($email) ?></td></tr>
        <tr><th>Секция:</th><td><?= htmlspecialchars($section) ?></td></tr>
        <tr><th>Дата рождения:</th><td><?= $birth_date ? htmlspecialchars($birth_date) : '—' ?></td></tr>
        <tr><th>Доклад:</th><td><?= $has_report ? 'Да' : 'Нет' ?></td></tr>
        <?php if ($has_report): ?>
        <tr><th>Тема доклада:</th><td><?= htmlspecialchars($report_title) ?></td></tr>
        <?php endif; ?>
      </table>
      <p style="text-align:center;">
        <a href="index.html">← Вернуться к форме</a> | 
        <a href="participants.php">Посмотреть всех участников</a>
      </p>
    </div>
    </body>
    </html>
    <?php

} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // duplicate entry
        die("Участник с таким email уже зарегистрирован.");
    }
    die("Ошибка базы данных: " . $e->getMessage());
}
