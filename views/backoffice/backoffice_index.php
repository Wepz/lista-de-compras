<?php
    session_start();
    if($_SESSION['can-area-administracao'] !== 1){
        header('Location: /');
    }
    
    require('../../db_config.php');

    //header('Location: /views/backoffice/populate_products.php')
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Backoffice</title>
        <?php
            require_once(ROOTPATH.'/views/layouts/base.php');
        ?>

    </head>
    <body>
        <?php
            require_once(ROOTPATH.'/views/layouts/header.php');
        ?>
        <main>
            <div class="container">
                <div class="form-controll">
                    <button class="btn btn-primary m-b-10"><a href="/views/backoffice/populate_products.php">Produtos</a></button><br>
                    <button class="btn btn-primary m-b-10"><a href="/views/backoffice/grupos-permissoes.php">Grupos de Utilizadores e Permissões</a></button><br>
                </div>
            </div>
        </main>
        <?php
            require_once(ROOTPATH.'/views/layouts/footer.php');
        ?> 
    </body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
</html>