<?php
    include_once __DIR__.'/database.php'; // Incluye la conexión

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    
    if(empty($producto)) {
        echo "Error: No se recibieron datos.";
        die();
    }

    // SE TRANSFORMA EL STRING DEL JASON A OBJETO
    $jsonOBJ = json_decode($producto);

    if(is_null($jsonOBJ)) {
        echo "Error: El JSON enviado no es válido.";
        die();
    }

    // Asignar variables y escapar strings para prevenir SQL Injection
    $nombre = $conexion->real_escape_string($jsonOBJ->nombre);
    $marca = $conexion->real_escape_string($jsonOBJ->marca);
    $modelo = $conexion->real_escape_string($jsonOBJ->modelo);
    $precio = (float) $jsonOBJ->precio; // Cast a float
    $detalles = $conexion->real_escape_string($jsonOBJ->detalles);
    $unidades = (int) $jsonOBJ->unidades; // Cast a int
    $imagen = $conexion->real_escape_string($jsonOBJ->imagen);

    
    /**
     * VALIDACIÓN DE EXISTENCIA EN EL SERVIDOR
     * (nombre Y marca) O (marca Y modelo) donde eliminado = 0
     */
    $sql_check = "SELECT * FROM productos WHERE 
                  ( (nombre = '{$nombre}' AND marca = '{$marca}') OR 
                    (marca = '{$marca}' AND modelo = '{$modelo}') ) 
                  AND eliminado = 0";

    if ( $result_check = $conexion->query($sql_check) ) {
        
        if($result_check->num_rows > 0) {
            // El producto ya existe
            echo "Error: El producto ya existe (Nombre/Marca o Marca/Modelo duplicados).";
            $result_check->free();
            $conexion->close();
            die();
        }
        $result_check->free();

    } else {
        echo "Error en la consulta de validación: " . $conexion->error;
        $conexion->close();
        die();
    }


    /**
     * INSERCIÓN A LA BASE DE DATOS
     * Si la validación pasó, procedemos a insertar
     */
    $sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
                   VALUES ('{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}')";

    if ($conexion->query($sql_insert)) {
        echo "Éxito: Producto agregado correctamente.";
    } else {
        echo "Error: No se pudo agregar el producto. " . $conexion->error;
    }

    $conexion->close();

?>