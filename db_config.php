<?php

   require_once('env.php');

    /* Database credentials. Assuming you are running MySQL
    server with default setting (user 'root' with no password) */
    $db_servername = $_ENV["DB_HOST"];
    $db_username = $_ENV["DB_USERNAME"];
    $db_password = $_ENV["DB_PASSWORD"];
    $db_name = $_ENV["DB_DATABASE"];
    
    /* Attempt to connect to MySQL database */
    $db_link = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
    
    // Check connection
    if($db_link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

?>