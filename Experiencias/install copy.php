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

    //----------------------------------------- Criar tabela de utilizadores -----------------------------------------//
    // sql to create table
    $users = "CREATE TABLE users (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VarChar(200) NOT NULL UNIQUE,
        email_verified INT DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    // use exec() because no results are returned
    $conn->exec($users);
    echo "Table users created successfully";
  } catch(PDOException $e) {
    echo $users . "<br>" . $e->getMessage();
  }
  //----------------------------------------- Criar tabela de utilizadores -----------------------------------------//
  




  $conn = null;
?>
