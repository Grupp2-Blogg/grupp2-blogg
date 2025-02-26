<?php

$dbservername = "193.181.23.31";
$dbname = "fishyblogg";
$dbusername = "user";
$dbpassword = "";



$dsn = "mysql:host=$dbservername;port=3306;dbname=$dbname";

try {

    $pdo = new PDO($dsn, $dbusername, $dbpassword);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    echo "DATABASE CONNECTION FAILED: " . $e->getMessage() . "<br><br>";
}
