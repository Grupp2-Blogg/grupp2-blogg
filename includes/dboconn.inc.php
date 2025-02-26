<?php

$config = require(__DIR__ . "includes/config.php");

try {

    $pdo = new PDO(
        "mysql:host={$config['db_server']};dbname={$config['db_name']};charset=utf8mb4",
        $config["db_username"],
        $config["db_password"],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    echo "DATABASE CONNECTION FAILED: " . $e->getMessage() . "<br><br>";
}
