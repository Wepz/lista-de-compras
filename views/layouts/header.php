<?php

//print_r($_SESSION);

//Quando o utilizador estiver Logado
if (isset($_SESSION["user"])) {
    
    // Query para ir buscar os dados do utilizador autenticado
    try {
        $get_auth_user = $db_link->prepare("SELECT * FROM users
                                            WHERE id = :authid ");       
    
        $data = [
            'authid' => $_SESSION["user"]
        ];

        $get_auth_user->execute($data);
        
        $auth_user = $get_auth_user->fetch();

    } catch (PDOException $e) {
        //throw $th;
    }
    // Fim da Query

    //print_r($auth_user["id"]);
    // Query para ir buscar o user_permissions
    try {
        $get_user_permissions = $db_link->prepare("SELECT * 
                                                FROM roles r 
                                                JOIN role_permission rp on r.id = rp.role_id
                                                JOIN permissions p on rp.permission_id = p.id 
                                                WHERE r.id = :auth_role_id");       

        $data = [
            'auth_role_id' => $auth_user['role_id']
        ];

        $get_user_permissions->execute($data);
        
        //$auth_user = $get_auth_user->fetch();
        $role = $get_user_permissions->fetch();

        if($role['permission_id'] == 1){
            $_SESSION['can-area-administracao'] = 1;
        }
    } catch (PDOException $e) {
        //throw $th;
    }
    // Fim da Query
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_logout"])){

        // remove all session variables
        session_unset();

        // destroy the session
        session_destroy();

        header('Location: /index.php');

    }

}

?>

<header>
    <div class="d-flex-center"></div>
    <div class="header-nav">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-4">
                    <div class="col-2">
                        <a href="/"><button class="btn btn-light w-100"><i class="fas fa-home"></i></button></a>
                    </div>
                </div>
                <div class="col-4 d-flex-end">
                    <?php if (isset($_SESSION["user"])): ?>
                        <div class="m-r-5 col-6">
                            <?php if($role['permission_id'] == 1): ?>
                                <a href="/views/backoffice/backoffice_index.php"><button class="btn btn-light w-100">Área de Administração</button></a>
                            <?php endif; ?>
                        </div>
                        <div class="m-r-5 cold-2">
                            <button class="btn btn-light w-100"><i class="fas fa-user"></i></button>
                        </div>
                        <div class="m-r-5 cold-2">
                            <button class="btn btn-light w-100"><div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                  <li><a class="dropdown-item" href="#">Action</a></li>
                                  <li><a class="dropdown-item" href="#">Another action</a></li>
                                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </div></button>
                            
                        </div>
                        <div class="m-r-5 col-2">
                            <form class="m-0" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <button class="btn btn-light w-100" type="submit" name="submit_logout">Sair</button>
                            </form>
                        </div>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>