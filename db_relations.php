<?php
   
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once(ROOTPATH.'/db_config.php');
    
    //Vai buscar dados á tabela de users
    //var $produto_id = ;

    $produto_tipo = "SELECT * 
    FROM produtos AS p
    LEFT JOIN tipos_produtos AS tp 
    	ON (p.tipo_id = tp.id)
    	WHERE p.id Like :produto";

    $data = [
        'produto' => $produto_id,
    ];



?>