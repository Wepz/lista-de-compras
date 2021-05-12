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
        $get_roles = $db_link->prepare("SELECT * FROM roles");          

            $get_roles->execute();
            
            $roles = $get_roles->fetchAll();

    } catch (PDOException $e) {
        //throw $th;
    }
    // Fim da Query

    // Query para ir buscar todos os product_types
    try {
        $get_permissions = $db_link->prepare("SELECT * FROM permissions");          

            $get_permissions->execute();
            
            $permissions = $get_permissions->fetchAll();
        

    } catch (PDOException $e) {
        //throw $th;
    }
    // Fim da Query

    //$name = $price = $tipo_id = $image_path = $stock = "";

    
    
    // delete Role
    if(isset($_GET['delete-role'])){
        
        try {
            $db_delete_role = $db_link->prepare("DELETE FROM roles 
                                                            WHERE id = :role_id"); 
                    
                    $data = [
                        'role_id' => $_GET['delete-role'],
                    ];
                        $db_delete_role->execute($data);
                        
                        //$products = $get_products->fetchAll();
                    header('Location: grupos-permissoes');
                    die();
        } catch (PDOException $e) {
            
        }
        
    }

    // delete Permission
    if(isset($_GET['delete-permission'])){
        
        try {
            $db_delete_role = $db_link->prepare("DELETE FROM permissions 
                                                            WHERE id = :permission_id"); 
                    
                    $data = [
                        'permission_id' => $_GET['delete-permission'],
                    ];
                        $db_delete_role->execute($data);
                        
                        //$products = $get_products->fetchAll();
                    header('Location: grupos-permissoes');
                    die();
        } catch (PDOException $e) {
            
        }
    }

    if(isset($_GET['edit-role-permission'])){
        //print_r($_SESSION['selected_role_permission']);
        if(isset($_SESSION['selected_role_permission'])){
            //print_r("entrei aqui");
            unset($_SESSION['selected_role_permission']);
            unset($_GET['edit-role-permission']);
            
        } else{
        //print_r("entrei");
            try {
                $db_role_permissions = $db_link->prepare("SELECT * 
                                                    FROM role_permission rp
                                                    JOIN permissions p on rp.permission_id = p.id
                                                    WHERE role_id = :role_id");

                $data = [
                    'role_id' => $_GET['edit-role-permission'],
                ];
                    $db_role_permissions->execute($data);

                    $selected_role_permissions = array();

                    $role_permissons = $db_role_permissions->fetchAll();
                    foreach ($role_permissons as $rp) {
                        array_push($selected_role_permissions, $rp['id']);
                    }
                    
                    $_SESSION['selected_role_permission'] = $_GET['edit-role-permission'];  
            } catch (PDOException $e) {
                //echo $e;
            }
       }
       //print_r($selected_role_permission);
    }

    if(isset($_GET['save-changes'])){
        
        
        try {
            $db_role_permissions = $db_link->prepare("SELECT * 
                                                FROM role_permission rp
                                                JOIN permissions p on rp.permission_id = p.id
                                                WHERE role_id = :role_id");
                    
            $data = [
                'role_id' => $_GET['edit-role-permission'],
            ];
                $db_role_permissions->execute($data);
                
                $role_permissions = $db_role_permissions->fetchAll();
                    
                    
        } catch (PDOException $e) {
            echo $e;
        }  
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Criar uma nova Role
        if(isset($_POST['submit_role']) && $_REQUEST['role_name'] && $_REQUEST['slug']) {
            try {
                $db_role = $db_link->prepare("INSERT INTO roles (role_name, slug)
                VALUES (:role_name, :slug)"); 
       
                $data = [
                    'role_name' => $_REQUEST['role_name'],
                    'slug' => $_REQUEST['slug'],
                ];
                    $db_role->execute($data);
                    
                    //$products = $get_products->fetchAll();
                header('Location: /views/backoffice/grupos-permissoes.php');
            } catch (PDOException $e) {
                //throw $th;
            }
            // Fim da Query
        }

        // Criar uma nova Permissão
        if(isset($_POST['submit_permission']) && $_REQUEST['permission'] && $_REQUEST['slug']) {

            try {
                $db_permission = $db_link->prepare("INSERT INTO permissions (permission, slug)
                VALUES (:permission, :slug)"); 
       
      
                $data = [
                    'permission' => $_REQUEST['permission'],
                    'slug' => $_REQUEST['slug'],
                ];
                    $db_permission->execute($data);
                    
                    //$products = $get_products->fetchAll();
                header('Location: grupos-permissoes.php');
            } catch (PDOException $e) {
                //throw $th;
            }
            // Fim da Query
        }

        if(isset($_POST['submit_role_permissions'])){
           
            foreach ($permissions as $permission) {
                print_r($permission['id']);
                    if(!isset($_POST['checkbox_'.$permission['id']])){
                        try {
                            $db_delete_role_permission = $db_link->prepare("DELETE FROM role_permission
                                                                            WHERE role_id = :role_id AND permission_id = :permissions_id"); 
                            
    
                            //print_r($_SESSION['selected_role_permission']);
                            //print_r($permission['id']);
                            //die;
    
                            $data = [
                                'role_id' => $_SESSION['selected_role_permission'],
                                'permission_id' => $permission['id'],
                            ];
                                $db_delete_role_permission->execute($data);
    
                                unset($_SESSION['selected_role_permission']);
                                
    
                                //$products = $get_products->fetchAll();
                            header('Location: grupos-permissoes.php');
                        } catch (PDOException $e) {
                            throw $e;
                        }
                    }
                 
                if(isset($_POST['checkbox_'.$permission['id']])){
                    
                    try {
                        $db_insert_role_permission = $db_link->prepare("INSERT INTO role_permission (role_id, permission_id)
                        VALUES (:role_id, :permission_id)"); 
                        
                        $data = [
                            'role_id' => $_SESSION['selected_role_permission'],
                            'permission_id' => $_POST['checkbox_'.$permission['id']],
                        ];
                            $db_insert_role_permission->execute($data);

                            
                            

                            //$products = $get_products->fetchAll();
                        
                    } catch (PDOException $e) {
                        throw $e;
                    }
                    
                    
                } 

            }
            unset($_SESSION['selected_role_permission']);
                //header('Location: grupos-permissoes.php');
        }
    }
   
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Grupos de Utilizadores e Permissões</title>
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
                            </div>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <div class="col-12 row">
                                    <div class="col-6">
                                        <h4>Grupos:</h4>

                                            <table class="table">
                                                <thead>
                                                  <tr>
                                                    <th></th>
                                                    <th scope="col">Nome</th>
                                                    <th scope="col">Slug</th>
                                                    <th></th>
                                                    <th></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td><input class="form-control" type="text" name="role_name" placeholder="nome"></td>
                                                        <td><input class="form-control" type="text" name="slug" placeholder="slug"></td>
                                                        <td><button class="btn btn-primary" type="submit" name="submit_role"><i class="far fa-plus-square"></i></button></td>
                                                        <td></td>
                                                    </tr>
                                                    <?php foreach ($roles as $role): ?>
                                                        <tr class="<?= isset($_GET['edit-role-permission']) && $role['id'] == $_SESSION['selected_role_permission'] ? 'yellowish-row' : '' ?>">
                                                            <td>
                                                                <a class="btn btn-warning" href="grupos-permissoes.php?edit-role-permission=<?= $role['id'] ?>">
                                                                    <i class="fas fa-th-large"></i>
                                                                </a>
                                                            </td>
                                                            <td><input class="form-control" type="text" name="name" value="<?= $role['role_name'] ?>"></td>
                                                            <td><input class="form-control" type="text" name="name" value="<?= $role['slug'] ?>"></td>
                                                            <td>
                                                                <a class="btn btn-success" href="grupos-permissoes.php?edit-role=<?= $role['id'] ?>">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-danger" href="grupos-permissoes.php?delete-role=<?= $role['id'] ?>">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <h4>Permissões:</h4>
                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                            <table class="table">
                                                <thead>
                                                  <tr>
                                                    <?php if(isset($_SESSION['selected_role_permission'])): ?>
                                                        <th></th>
                                                    <?php endif; ?>
                                                    <th scope="col">Permissão</th>
                                                    <th scope="col">Slug</th>
                                                    <th></th>
                                                    <th></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <?php if(isset($_SESSION['selected_role_permission'])): ?>
                                                            <th></th>
                                                        <?php endif; ?>
                                                        <td><input class="form-control" type="text" name="permission" placeholder="nome"></td>
                                                        <td><input class="form-control" type="text" name="slug" placeholder="slug"></td>
                                                        <td><button class="btn btn-primary" type="submit" name="submit_permission"><i class="far fa-plus-square"></i></button></td>
                                                        <td></td>
                                                    </tr>
                                                    <?php foreach ($permissions as $permission): ?>
                                                    
                                                        <?php if(!empty($selected_role_permissions)): ?>
                                                            <tr class="<?= in_array($permission['id'], $selected_role_permissions) ? 'yellowish-row' : '' ?>">
                                                                <td><input class="form-check-input custom-checkbox" type="checkbox" name="checkbox_<?= $permission['id'] ?>" <?= in_array($permission['id'], $selected_role_permissions) ? 'checked' : '' ?> value="<?= $permission['id'] ?>"></td>
                                                                <td><input class="form-control" type="text" name="name" value="<?= $permission['permission']?>"></td>
                                                                <td><input class="form-control" type="text" name="name" value="<?= $permission['slug'] ?>"></td>
                                                                <td>
                                                                    <a class="btn btn-success" href="grupos-permissoes.php?edit-permission=<?= $permission['id'] ?>">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-danger" href="grupos-permissoes.php?delete-permission=<?= $permission['id'] ?>">
                                                                        <i class="far fa-trash-alt"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>  
                                                        <?php else: ?>
                                                        <tr>
                                                            <?php if(isset($_SESSION['selected_role_permission'])): ?>
                                                                <td><input class="form-check-input custom-checkbox" type="checkbox" name="checkbox_<?= $permission['id'] ?>" value="<?= $permission['id'] ?>" > </td>
                                                            <?php endif; ?>
                                                            <td><input class="form-control" type="text" name="name" value="<?= $permission['permission'] ?>"></td>
                                                            <td><input class="form-control" type="text" name="name" value="<?= $permission['slug'] ?>"></td>
                                                            <td>
                                                                <a class="btn btn-success" href="grupos-permissoes.php?edit-permission=<?= $permission['id'] ?>">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-danger" href="grupos-permissoes.php?delete-permission=<?= $permission['id'] ?>">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                                            
                                    </div>
                                    <?php if(isset($_GET['edit-role-permission'])): ?>
                                        <div class="col-12">
                                            <button class="btn btn-warning" type="submit" name="submit_role_permissions">Guardar Alterações</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
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