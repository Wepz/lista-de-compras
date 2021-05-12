<?php

//Autoloader
if (file_exists('vendor/autoload.php')) {
    require_once('vendor/autoload.php');
}

// dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


    $servername = $_ENV["DB_HOST"];
    $username = $_ENV["DB_USERNAME"];
    $password = $_ENV["DB_PASSWORD"];
    $dbname = $_ENV["DB_DATABASE"];

  
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    
    $conn = null;
?>