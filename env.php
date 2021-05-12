<?php

    define('ROOTPATH', __DIR__);
    
    //Autoloader, utiliza a biblioteca que existe no vendor para poder aceder ao meu .env
    if (file_exists(ROOTPATH.'/vendor/autoload.php')) {
        require_once(ROOTPATH.'/vendor/autoload.php');
    }

    // dotenv
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    // $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    
?>