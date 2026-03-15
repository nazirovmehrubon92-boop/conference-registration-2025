<?php
// config.php
define('DB_HOST',     'localhost');
define('DB_NAME',     'conference');
define('DB_USER',     'root');          // поменяйте на своего пользователя
define('DB_PASSWORD', '');              // поменяйте на свой пароль
define('DB_CHARSET',  'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
        DB_USER,
        DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
