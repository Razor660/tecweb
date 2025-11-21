<?php
    use TECWEB\MYAPI\Create\Create; // Usar la nueva clase
    require_once __DIR__.'/../vendor/autoload.php'; // Usar el autoloader

    $productos = new Create('marketzone'); // Instanciar la nueva clase
    $productos->add( json_decode( json_encode($_POST) ) );
    echo $productos->getData();
?>