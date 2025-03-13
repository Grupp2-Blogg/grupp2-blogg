<?php

// class Dbh 
// {
//     protected function connect() 
//     {
//         try 
//         {
//             $username = "root";  
//             $password = "";  
//             $dbh = new PDO("mysql:host=localhost;dbname=fishyblogg;charset=utf8", $username, $password);
//             $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             return $dbh;
//         } 
//         catch (PDOException $e) 
//         {
//             die("Error!: " . $e->getMessage());
//         }
//     }
// }