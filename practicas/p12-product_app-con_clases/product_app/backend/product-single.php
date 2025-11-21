<?php
    use TECWEB\MYAPI\Read\Read; // Usar la nueva clase
    require_once __DIR__.'/../vendor/autoload.php'; // Usar el autoloader

    $productos = new Read('marketzone'); // Instanciar la nueva clase
    $productos->single( $_POST['id'] );
    echo $productos->getData();
?>