<?php
require 'config.php';

$stmt = $pdo->query("
    SELECT id, full_name, phone, email, section, 
           birth_date, has_report, report_title, created_at
    FROM participants 
    ORDER BY created_at DESC
");
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Список участников</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-top: 1.5rem; 
    }
    th, td { 
      padding: 0.9rem; 
      border: 1px solid #e5e7eb; 
      text-align: left; 
    }
    th { 
      background: #f3f4f6; 
      font-weight: 600; 
    }
    tr:nth-child(even) { background: #f9fafb; }
    .yes { color: #15803d; font-weight: bold; }
    .no  { color: #b91c1c; }
  </style>
</head>
<body>
<div class="container">
  <h1>Зарегистрированные участники</h1>
  
  <?php if (count($rows) === 0): ?>
    <p style="text-align:center; color:#666;">Пока никто не зарегистрировался</p>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>ФИО</th>
        <th>Телефон</th>
        <th>Email</th>
        <th>Секция</th>
        <th>Доклад</th>
        <th>Дата регистрации</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['section']) ?></td>
        <td class="<?= $row['has_report'] ? 'yes' : 'no' ?>">
          <?= $row['has_report'] ? 'Да' : 'Нет' ?>
          <?php if ($row['has_report'] && $row['report_title']): ?>
            <br><small><?= htmlspecialchars($row['report_title']) ?></small>
          <?php endif; ?>
        </td>
        <td><?= date('d.m.Y H:i', strtotime($row['created_at'])) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <p style="text-align:center; margin-top:2rem;">
    <a href="index.html">← Зарегистрироваться</a>
  </p>
</div>
</body>
</html>
