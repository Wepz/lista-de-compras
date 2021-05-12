<?php

session_start();
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

define('REGISTERPATH', __DIR__);

require_once('../db_config.php');
//defenir variáveis e declarar como empty value no inicio
$name = $email = $password = $confirm_password = "";

//----------------------------------------- Funções -----------------------------------------//
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
//----------------------------------------- Funções -----------------------------------------//

//Assim que o post request for submetido define variaveis
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //valida se o campo preenchido é valido
    if (empty($_POST["name"])) {
        $nameErr = "É necessário introduzir um Nome válido";
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "É necessário introduzir um Email válido";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "É necessário introduzir uma Palavra-Passe válida";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "É necessário introduzir uma Palavra-Passe válida";
    } else {
        if ($_POST["confirm_password"] != $_POST["password"]) {
            $confirmPasswordErr = "A Palavra-Passe deve coicidir";
        } else {
            $confirm_password = test_input($_POST["confirm_password"]);
        }
    }
    
    //Se todos os campos do formulário forem preenchidos 
    //corretamente grava os dados na base de dados, redireciona para outra pagina e die(); para parar os restantes processos
    if($name && $email && $password && $confirm_password){

        try {
            
            // set the PDO error mode to exception
            $db_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $db_link->beginTransaction();

                $db_validation = $db_link->prepare("SELECT max(usernames) AS usernames, max(emails) AS emails FROM 
                    (
                        SELECT count(id) AS usernames, 0 AS emails 
                            FROM users 
                            WHERE username = :name
                        UNION 
                        SELECT 0 AS usernames, count(id) AS emails 
                            FROM users 
                            WHERE email = :email
                    ) AS FINAL_TABLE_NAME");
                
                $data = [
                    'name' => $name,
                    'email' => $email,
                ];
                //print_r($name);
                
                $db_validation->execute($data);
                
                $validation_result = $db_validation->fetch(PDO::FETCH_ASSOC);
                
            // validar se já existe algum registo com esse user ou email
            if ($validation_result['usernames'] > 0) {
                $flagName = "flag";
                $duplicateErr = "Já existe um registo com esse nome";
            }
            if ($validation_result['emails'] > 0) {
                $flagEmail = "flag";
                if(!empty($duplicateErr)){
                    $duplicateErr = $duplicateErr." e com esse email";
                }else{
                    $duplicateErr = "Já existe um registo com esse email";
                }
            }
            

            $db_users = "INSERT INTO users (username, password, email)
                VALUES (:name, :password, :email)";

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $data = [
                'name' => $name,
                'password' => $hash,
                'email' => $email,
            ];
            // use exec() because no results are returned
            $db_link->prepare($db_users)->execute($data);
            $id = $db_link->lastInsertId();
            $db_link->commit();
            
            echo "New record created successfully";
            $_SESSION["user"] = $id;
            $_SESSION['loggedin'] = time();
            header('Location: /views/shop.php');
          } catch(PDOException $e) {

          }
    };
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <div class="form-control">
                        <h2>Registo</h2>
                        <p>Por favor preencha o formulário para criar uma conta</p>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="mb-3">
                                <label>Utilizador</label>
                                <input type="text" name="name" class="form-control <?php if(!empty($flagName) || !empty($nameErr)): ?>is-invalid<?php endif; ?>" value="<?php echo $name; ?>">
                                <?php  if(!empty($nameErr)): ?> 
                                    <div class="alert alert-info"><?= $nameErr; ?></div> 
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control <?php if(!empty($flagEmail) || !empty($emailErr)): ?>is-invalid<?php endif; ?>" value="<?php echo $email; ?>">
                                <?php if(!empty($emailErr)): ?>
                                    <div class="alert alert-info"> <?php echo $emailErr; ?> </div>
                                <?php endif; ?>
                                
                            </div>
                            <div class="mb-3 ">
                                <label>Palavra-Passe</label>
                                <input type="password" name="password" class="form-control <?php if(!empty($passwordErr)): ?>is-invalid<?php endif; ?>" value="<?php echo $password; ?>">
                                <?php if(!empty($passwordErr)): ?>
                                    <div class="alert alert-info"> <?php echo $passwordErr; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 ">
                                <label>Confirme Password</label>
                                <input type="password" name="confirm_password" class="form-control <?php if(!empty($confirmPasswordErr)): ?>is-invalid<?php endif; ?>" value="<?php echo $confirm_password; ?>">
                                <?php if(!empty($confirmPasswordErr)): ?>
                                    <div class="alert alert-info"> <?php echo $confirmPasswordErr; ?> </div>
                                <?php endif; ?>    
                                 
                                
                            </div>
                            <div class="mb-3">
                                <input type="submit" class="btn btn-primary" value="Submeter">
                            </div>
                            <p>Ja tem uma conta? <a href="/views/login.php">Faça login aqui</a></p>
                            <?php if(!empty($duplicateErr)): ?>
                                <div class="alert alert-info"> <?php echo $duplicateErr; ?> </div>
                            <?php endif; ?>
                            <?php if(!empty($e)): ?>
                                <!-- <div class="alert alert-info"> <?php echo $e; ?> </div> -->
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
    <script src="/js/app.js"></script>
</html>