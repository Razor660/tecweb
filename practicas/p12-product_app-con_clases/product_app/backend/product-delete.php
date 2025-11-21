<?php
    use TECWEB\MYAPI\Delete\Delete; // Usar la nueva clase
    require_once __DIR__.'/../vendor/autoload.php'; // Usar el autoloader

    $productos = new Delete('marketzone'); // Instanciar la nueva clase
    $productos->delete( $_POST['id'] );
    echo $productos->getData();
?>