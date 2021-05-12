<?php

    //importar db_config
    require_once('../db_config.php');

    session_start();

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    // Query para ir buscar todos os produtos
    try {
        $get_products = $db_link->prepare("SELECT * FROM products");          

            $get_products->execute();
            
            $products = $get_products->fetchAll();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    // Fim da Query

    // Query para ir buscar todos os favoritos deste utilizador
    try {
        $db_validation = $db_link->prepare("SELECT * 
                                        FROM favorites WHERE user_id = :user_id");
        
        
        $db_validation->bindParam(':user_id', $_SESSION['user']);
        $db_validation->execute();

        $favorites = $db_validation->fetchAll();
        //print_r($favorites);
    } catch (PDOException $e) {
        //throw $th;
    }

    $favorite_list = [];
    foreach ( $favorites as $fav) {
        array_push($favorite_list, $fav['product_id']);
    }

    function addfav($fav, $db_link, $favorites){


        foreach ($favorites as $fp) {
            // Se encontrar algum favorito na minha lista com o id do artigo carregado
            if ($fav == $fp["product_id"]) {
                try {
                    $db_delete_favorites = $db_link->prepare("DELETE FROM favorites 
                                                            WHERE user_id = :user_id AND product_id = :product_id"); 
                    
                    $data = [
                        'user_id' => $_SESSION['user'],
                        'product_id' => $fav,
                    ];
                        $db_delete_favorites->execute($data);
                        print_r($db_delete_favorites);
                        
                        //$products = $get_products->fetchAll();
                    header('Location: shop.php');
                    die();
                } catch (PDOException $e) {
                    
                }
                // Fim da Query
            }
        }

        try {
            $db_favorites = $db_link->prepare("INSERT INTO favorites (product_id, user_id)
            VALUES (:product_id, :user_id)"); 

            $data = [
                'product_id' => $fav,
                'user_id' => $_SESSION['user'],
            ];
                $db_favorites->execute($data);
                
                //$products = $get_products->fetchAll();
            header('Location: shop.php');
        } catch (PDOException $e) {
            //throw $th;
        }
        // Fim da Query

    }

    function addcart($quantity, $prod_id, $db_link){
        
        try {
            $db_cart = $db_link->prepare("INSERT INTO cart (user_id, product_id, quantity)
            VALUES (:user_id, :product_id, :quantity)"); 
   
  
            $data = [
                'user_id' => $_SESSION['user'],
                'product_id' => $prod_id,
                'quantity' => $quantity,
            ];
                $db_cart->execute($data);
                
                //$products = $get_products->fetchAll();
            header('Location: /');
        } catch (PDOException $e) {
            //throw $th;
        }
        // Fim da Query
    }

    if (isset($_GET['fav'])) {
        addfav($_GET['fav'], $db_link, $favorites);
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_cart"])) {
        addcart($_POST["quantity"], $_POST["prod_id"], $db_link);
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Registo</title>

        <?php
            require_once(ROOTPATH.'/views/layouts/base.php');
        ?>
        
    </head>
    <body>
        <?php
            require_once(ROOTPATH.'/views/layouts/header.php');
        ?>
        <main>
            <div class="row justify-content-center">
                <div class="col-7 shop-menu">
                    <div class="row">
                        <?php foreach ($products as $p): ?>
                            <figure class="col-md-4 col-sm-12"><img src="/ficheiros/imagens/<?= $p['image_path'] ?>">
                                <figcaption>
                                    <?= $p['name'] ?>&nbsp<strong><?= $p['price'] ?>€/Kg</strong>
                                    <a href="shop.php?fav=<?= $p['id'] ?>">
                                        <button class="<?= (in_array($p['id'], $favorite_list) ? 'btn btn-pink' : 'btn btn-light') ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </a>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="d-flex-center">
                                        <input name="quantity" type="number" class="form-control"><input class="d-none" name="prod_id" type="number" value="<?= $p['id'] ?>">
                                        <button type="submit" name="submit_cart" value="Submit" class="btn btn-light"><i class="fas fa-shopping-cart"></i></button>
                                    </form>
                                </figcaption>
                            </figure>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div>
        </main>
        <?php
            require_once(ROOTPATH.'/views/layouts/footer.php');
        ?> 
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
</html>