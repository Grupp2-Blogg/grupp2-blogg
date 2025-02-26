<?php

$dbservername = "localhost";
$dbname = "fishyblogg";
$dbusername = "root";
$dbpassword = "";


$dsn = "mysql:host=$dbservername;dbname=$dbname";

try {

    $pdo = new PDO($dsn, $dbusername, $dbpassword);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    echo "DATABASE CONNECTION FAILED: " . $e->getMessage() . "<br><br>";
}
