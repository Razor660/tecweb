<?php
/*
    include_once __DIR__.'/database.php';

    $data = array(
        'exists' => false,
        'message' => 'Nombre disponible'
    );

    if( isset($_POST['nombre']) ) {
        $nombre = $_POST['nombre'];
        
        // Evitar inyección SQL
        $nombre = $conexion->real_escape_string($nombre);
        
        // Si se está editando (se envía un ID), excluir el ID actual de la búsqueda
        // para no decir que el nombre "ya existe" cuando es el mismo producto.
        $idClause = "";
        if ( isset($_POST['id']) && !empty($_POST['id']) ) {
            $id = $conexion->real_escape_string($_POST['id']);
            $idClause = " AND id != {$id}";
        }

        $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0{$idClause}";
	    $result = $conexion->query($sql);
        
        if ($result->num_rows > 0) {
            $data['exists'] = true;
            $data['message'] = 'Ese nombre de producto ya existe';
        }

        $result->free();
        $conexion->close();
    }

    // Devolver siempre una respuesta JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
    /*/

    // b. Namespace e inclusión
    use myapi\Products;
    require_once __DIR__.'/myapi/Products.php';

    // Se instancia la clase
    $products = new Products();

    // Se llama al nuevo método checkName
    $resultData = $products->checkName(
        $_POST['nombre'], 
        isset($_POST['id']) ? $_POST['id'] : null
    );

    // e. Se devuelven los datos (sin usar getData(), sino el resultado directo)
    echo json_encode($resultData, JSON_PRETTY_PRINT);
?>