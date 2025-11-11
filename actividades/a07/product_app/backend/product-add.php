<?php
    /*
    // a. TODO EL CÓDIGO ORIGINAL COMENTADO
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $data = array(
        'status'  => 'error',
        'message' => 'Ya existe un producto con ese nombre'
    );
    if(isset($_POST['nombre'])) {
        // SE TRANSFORMA EL POST A UN STRING EN JSON, Y LUEGO A OBJETO
        $jsonOBJ = json_decode( json_encode($_POST) );
        // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
        $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
	    $result = $conexion->query($sql);
        
        if ($result->num_rows == 0) {
            $conexion->set_charset("utf8");
            $sql = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
            if($conexion->query($sql)){
                $data['status'] =  "success";
                $data['message'] =  "Producto agregado";
            } else {
                $data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($conexion);
            }
        }

        $result->free();
        // Cierra la conexion
        $conexion->close();
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
    */

    // b. Namespace e inclusión
    use myapi\Products;
    require_once __DIR__.'/myapi/Products.php';

    // c. Instancia de Products
    $products = new Products();

    // d. Se valida, se convierte a objeto y se usa el método add()
    if(isset($_POST['nombre'])) {
        // La clase espera un objeto, no un array asociativo
        $jsonOBJ = json_decode( json_encode($_POST) );
        $products->add($jsonOBJ);
    }

    // e. Se devuelve el JSON
    echo $products->getData();
?>