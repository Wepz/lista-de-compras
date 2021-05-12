<?php

    session_start();
    
    if($_SESSION['can-area-administracao'] !== 1){
        header('Location: /');
    }
    
    require('../../db_config.php');

    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);

    //----------------------------------------- Funções -----------------------------------------//
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
    //----------------------------------------- Fim das Funções -----------------------------------------//
      
    // Query para ir buscar todos os produtos
    try {
        $get_products = $db_link->prepare("SELECT * FROM products");          

            $get_products->execute();
            
            $products = $get_products->fetchAll();

    } catch (PDOException $e) {
        //throw $th;
    }
    // Fim da Query

    // Query para ir buscar todos os product_types
    try {
        $get_product_types = $db_link->prepare("SELECT * FROM product_types");          

            $get_product_types->execute();
            
            $product_types = $get_product_types->fetchAll();
        

    } catch (PDOException $e) {
        //throw $th;
    }
    // Fim da Query

    $name = $price = $tipo_id = $image_path = $stock = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if (empty($_POST["name"])) {
            $nameErr = "É necessário introduzir um Nome válido";
        } else {
            $name = test_input($_POST["name"]);
        }
        if (empty($_POST["price"])) {
            $priceErr = "É necessário introduzir um Nome válido";
        } else {
            $price = test_input($_POST["price"]);
        }

        if (empty($_POST["tipo_id"])) {
            $tipo_idErr = "É necessário introduzir um Nome válido";
        } else {
            $tipo_id = test_input($_POST["tipo_id"]);
        }

        if (empty($_POST["image_path"])) {
            $image_pathErr = "É necessário introduzir um Nome válido";
        } else {
            $image_path = test_input($_POST["image_path"]);
        }

        if (empty($_POST["stock"])) {
            $stockErr = "É necessário introduzir um Nome válido";
        } else {
            $stock = test_input($_POST["stock"]);
        }
        
        if($name && $price && $tipo_id && $image_path && $stock){
            try {

                $db_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $db_validation = $db_link->prepare("SELECT *  
                                FROM products 
                                WHERE name = :name
                     ");
                
                $data = [
                    'name' => $name,
                ];
                //print_r($name);
                
                $db_validation->execute($data);
                
                $validation_result = $db_validation->fetch(PDO::FETCH_ASSOC);

            // validar se já existe algum registo com esse nome
            if (!empty($validation_result)) {
                $duplicateName = "Já existe um registo com esse nome";
            }

                $create_product = $db_link->prepare("INSERT INTO products (name, price, tipo_id, image_path, stock)
                VALUES (:name, :price, :tipo_id, :image_path, :stock)");

                $data = [
                    'name' => $name,
                    'price' => $price,
                    'tipo_id' => $tipo_id,
                    'image_path' => $image_path,
                    'stock' => $stock 
                ];

                $create_product->execute($data);

                header('Location: '.$_SERVER['PHP_SELF']);
                echo "New record created successfully";
              } catch(PDOException $e) {
                //echo $db_link . "<br>" . $e->getMessage();
              }
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Produtos</title>
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
                <div class="row justify-content-md-center">
                    <div class="col-12 col-md-12">
                        <div class="form-control">
                            <div class="form-header">
                                <h2>Produtos</h2>
                            </div>
                            
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Preço</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Imagem</th>
                                        <th scope="col">Stock</th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input class="form-control" type="text" name="name"></td>
                                            <td><input class="form-control" type="text" name="price"></td>
                                            <td><select class="form-select" name="tipo_id">
                                                    <option value="">Selecione...</option>
                                                    <?php foreach ($product_types as $p_type): ?>
                                                        <option value="<?= $p_type['id'] ?>"><?= $p_type['slug'] ?></option>
                                                    <?php endforeach; ?>
                                                    
                                                </select>
                                            </td>
                                            <td><input class="form-control" type="text" name="image_path"></td>
                                            <td><input class="form-control" type="number" name="stock"></td>
                                            <td><input class="form-control" type="submit" class="btn btn-primary" value="Novo Produto"></td>
                                        </tr>
                                        <?php if (!empty($products)): ?>
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td><?= $product['name'] ?></td>
                                                    <td><?= $product['price'] ?></td>
                                                    <td><?php foreach ($product_types as $p_type):
                                                            if ($p_type["id"] == $product['tipo_id']):
                                                                echo $p_type["slug"];
                                                            endif;
                                                        endforeach;?>
                                                    </td>
                                                    <td><?= $product['image_path'] ?></td>
                                                    <td><?= $product['stock'] ?></td>
                                                    <td></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <?php if(!empty($duplicateName)): ?>
                                    <div class="alert alert-info"> <?php echo $duplicateName; ?> </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php
            require_once(ROOTPATH.'/views/layouts/footer.php');
        ?> 
    </body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="../../js/app.js"></script>
</html>