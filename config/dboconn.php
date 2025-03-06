<?php

$config = require(__DIR__ . "/config.php");



try {

    $pdo = new PDO(
        "mysql:host={$config['db_server']};dbname={$config['db_name']};charset=utf8mb4",
        $config["db_username"],
        $config["db_password"],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    try{
        $host = "localhost";
        $db = "fishyblogg";
        $username = "root";
        $password = "";

        $lokalPdo = new PDO("mysql:host=$host;dbname=$db;", $username, $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        );
    }
    catch(PDOException $local_e){
        echo "LOCAL DATABASE CONNECTION FAILED: " . $local_e->getMessage() . "<br><br>";
        die();
    }
    
    echo "BOTH LOCAL AND VIRTUAL DATABASE CONNECTION FAILED: " . $e->getMessage() . "<br><br>";
    die();
}



// När rasmus är klar lägg in detta

// class Database{
//     private $pdo;
//     private $config;

//     public function __construct(){
//         $this->config = require(__DIR__ . "/config.php");
//         $this->connect();
//     }


//     function connect(){
//         try{
//             $this->pdo = new PDO(
//                 "mysql:host={$this->config['db_server']};dbname={$this->config['db_name']};charset=utf8mb4",
//                 $this->config["db_username"],
//                 $this->config["db_password"],
//                 [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
//             );
//         }catch(PDOException $e){
//             $this->connectLocal($e);
//         }
//     }

//     function connectLocal(PDOException $e){
//         try{
//             $host = "localhost";
//             $db = "fishyblogg";
//             $username = "root";
//             $password = "";

//             $this->pdo = new PDO(
//                 "mysql:host=$host;dbname=$db;charset=utf8mb4",
//                 $username,
//                 $password,
//                 [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
//             );

//         }catch(PDOException $local_e){
//             die("BOTH LOCAL AND VIRTUAL DATABASE CONNECTION FAILED: " . $e->getMessage() . "<br>" . $local_e->getMessage());
//         }
//     }

//     public function getConnection(){
//         return $this->pdo;
//     }
// }