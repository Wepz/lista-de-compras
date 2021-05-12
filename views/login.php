<?php

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

//importar db_config
require_once('../db_config.php');

session_start();

if (!empty($_SESSION["user_email"])) {
    header('Location: /views/login.php');
}

$_SESSION["user_email"] = "";

$email = $password = "";

//----------------------------------------- Funções -----------------------------------------//
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
//----------------------------------------- Funções -----------------------------------------//

if($_SERVER["REQUEST_METHOD"] == "POST"){

    //valida se o campo preenchido é valido
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

    if ($email && $password) {
        try {
            
            // set the PDO error mode to exception
            $db_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // $login_query = $db_link->prepare("SELECT *
            // FROM users as u WHERE u.email = :email
            // AND u.password = :password");
            $login_query = $db_link->prepare("SELECT *
            FROM users as u WHERE u.email = :email");

            $data = [
                'email' => $email,
            ];

            $login = $login_query->execute($data);
            $result = $login_query->fetch(PDO::FETCH_ASSOC);

            
            if(password_verify($password, $result['password'])){
                echo "Logged In Successfully";
                $_SESSION["user"] = $result['id'];
                $_SESSION['loggedin'] = time();
                header('Location: /index.php');
            } else{
                $loginErr = "Dados de acesso inválidos, verifique se introduziu corretamente o e-mail e a palavra-passe";
            }
          } catch(PDOException $e) {
            
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

        <title>Login</title>
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
                            <h2>Login</h2>
                            <p>Por favor introduza os seus dados de acesso</p>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                                    <?php if(!empty($emailErr)): ?>
                                        <div class="alert alert-info"> <?php echo $emailErr; ?> </div>
                                    <?php endif; ?>
                                    
                                </div>
                                <div class="mb-3 ">
                                    <label>Palavra-Passe</label>
                                    <input type="password" name="password" class="form-control" value="">
                                    <?php if(!empty($passwordErr)): ?>
                                        <div class="alert alert-info"> <?php echo $passwordErr; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-primary" value="Login">
                                </div>
                                <p>Ainda não tem uma conta? <a href="/views/register.php">Registe-se aqui</a></p>
                                <?php if(!empty($e)): ?>
                                    <div class="alert alert-info"> <?php echo $e; ?> </div>
                                <?php endif; ?>
                                <?php if(!empty($loginErr)): ?>
                                    <div class="alert alert-info"> <?php echo $loginErr; ?> </div>
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