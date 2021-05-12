<?php

    session_start();
    //$user = $_REQUEST['user'];

    if (!empty($user)) {
        header('refresh:3; url=/views/shop.php');
    } else{
        header('refresh:3; url=/views/Login.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo-Success</title>
    <?php
        require_once(ROOTPATH.'/views/layouts/base.php');
    ?>
</head>
<body>
    Registo Efectuado com Sucesso
</body>
</html>