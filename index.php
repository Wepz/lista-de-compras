<?php
    
    //inicio da sessão
    session_start();
    
    if (isset($_SESSION['loggedin']) && (time() - $_SESSION['loggedin'] > 1800)) {
        // last request was more than 30 minutes ago
        session_unset($_SESSION['loggedin']);     // unset $_SESSION variable for the run-time 
        session_destroy($_SESSION['loggedin']);   // destroy session data in storage
    } 

    //validar se na sessão existe alguma variavel de utilizador, 
    
    if(isset($_SESSION["user"])){
        header('Location: /views/shop.php');
        die();
    }

    //redirect para o /register
    header('Location: /views/login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>