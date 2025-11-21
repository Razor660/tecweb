<?php
    use TECWEB\MYAPI\Update\Update; // Usar la nueva clase
    require_once __DIR__.'/../vendor/autoload.php'; // Usar el autoloader

    $productos = new Update('marketzone'); // Instanciar la nueva clase
    $productos->edit( json_decode( json_encode($_POST) ) );
    echo $productos->getData();
?>