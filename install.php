<?php

require_once('db_config.php');

function tableExists($pdo, $table) {

  // Try a select statement against the table
  // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
  try {
      $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
  } catch (Exception $e) {
      // We got an exception == table not found
      return FALSE;
  }

  // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
  return $result !== FALSE;
}

try {

    // set the PDO error mode to exception
    $db_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //----------------------------------------- Criar tabela de permissions -----------------------------------------//
    // sql para criar a tabela
      $permissions = "CREATE TABLE IF NOT EXISTS permissions (
          id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
          permission VARCHAR(200),
          slug VARCHAR(200)
      )";

      $db_link->exec($permissions);
      echo "Table permissions created successfully";
    //----------------------------------------- Fim tabela de permissions -----------------------------------------//

    //----------------------------------------- Criar tabela de roles -----------------------------------------//
    // sql para criar a tabela
      $roles = "CREATE TABLE IF NOT EXISTS roles (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        role_name VARCHAR(200),
        slug VARCHAR(200)
      )";

      $db_link->exec($roles);
      echo "Table roles created successfully";
    //----------------------------------------- Fim tabela de roles -----------------------------------------//

    //----------------------------------------- Criar tabela de role_permission -----------------------------------------//
    // sql para criar a tabela
    $role_permission = "CREATE TABLE IF NOT EXISTS role_permission (
      role_id INT,
      permission_id INT,
      PRIMARY KEY (role_id, permission_id)
    )";

    $db_link->exec($role_permission);
    echo "Table role_permission created successfully";
  //----------------------------------------- Fim tabela de role_permission -----------------------------------------//

    //----------------------------------------- Criar tabela de users -----------------------------------------//
    // sql para criar a tabela
      $users = "CREATE TABLE IF NOT EXISTS users (
          id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
          username VARCHAR(50) NOT NULL UNIQUE,
          password VARCHAR(200) NOT NULL,
          email VarChar(200) NOT NULL UNIQUE,
          role_id INT,
          email_verified INT DEFAULT 1,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )"; 
      $db_link->exec($users);
      echo "Table users created successfully";
    //----------------------------------------- Fim tabela de users -----------------------------------------//

    //----------------------------------------- Criar tabela de product_types -----------------------------------------//
    // sql para criar a tabela
      $product_types = "CREATE TABLE IF NOT EXISTS product_types (
          id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
          type VARCHAR(200),
          slug VARCHAR(200)
      )";

      $db_link->exec($product_types);
      echo "Table product_types created successfully";
    //----------------------------------------- Fim tabela de product_types -----------------------------------------//
    
    //----------------------------------------- Criar tabela de products -----------------------------------------//
    // sql para criar a tabela
    $products = "CREATE TABLE IF NOT EXISTS products (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(200) NOT NULL UNIQUE,
        price VARCHAR(200),
        tipo_id INT,
        image_path VARCHAR(200),
        stock VARCHAR(200)
    )";

    $db_link->exec($products);
    echo "Table products created successfully";
  //----------------------------------------- Fim tabela de produtos -----------------------------------------//
  
  //----------------------------------------- Criar tabela de cart -----------------------------------------//
    // sql para criar a tabela
      $cart = "CREATE TABLE IF NOT EXISTS cart (
          id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
          user_id VARCHAR(200),
          product_id VARCHAR(200),
          quantity VARCHAR(200),
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )";

      $db_link->exec($cart);
      echo "Table cart created successfully";
    //----------------------------------------- Fim tabela de cart -----------------------------------------//

    //----------------------------------------- Criar tabela de favorites -----------------------------------------//
    // sql para criar a tabela
      $favorites = "CREATE TABLE IF NOT EXISTS favorites (
          product_id INT,
          user_id INT,
          PRIMARY KEY (product_id, user_id)
      )";

      $db_link->exec($favorites);
      echo "Table favorites created successfully";
    //----------------------------------------- Fim tabela de favorites -----------------------------------------//

  } catch(PDOException $e) {
    //echo $favorites . "<br>" . $e->getMessage();
  }
  
  $db_link = null;
?>
