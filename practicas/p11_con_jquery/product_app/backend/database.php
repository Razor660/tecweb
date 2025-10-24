<?php
    $conexion = @mysqli_connect(
        'localhost',
        'root',  
        '@Nothing30', 
        'marketzone'
    );

    /**
     * NOTA: si la conexión falló $conexion contendrá false
     **/
    if(!$conexion) {
        die('¡Base de datos NO conectada!');
    }
    
    // Aseguramos que la conexión maneje UTF-8
    $conexion->set_charset("utf8");
?>