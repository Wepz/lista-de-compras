<?php
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
    //----------------------------------------- Update tabela users -----------------------------------------//

    if (!count($conn->query("SHOW COLUMNS FROM users LIKE 'email' ")->fetchAll())) {
        
        $users = "ALTER TABLE users
        ADD email VarChar(200) NOT NULL UNIQUE";

        $conn->exec($users);
        echo "Table users Updated successfully";
    }
    
    
    //----------------------------------------- Update tabela users -----------------------------------------//


  } catch(PDOException $e) {
    echo $users . "<br>" . $e->getMessage();
  }

  ?>