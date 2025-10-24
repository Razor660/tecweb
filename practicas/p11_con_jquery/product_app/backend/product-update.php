<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    $data = array(
        'status'  => 'error',
        'message' => 'Error: No se recibieron datos válidos.'
    );

    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JASON A OBJETO
        $jsonOBJ = json_decode($producto);

        // Validar que el JSON se decodificó y tiene 'id' y 'nombre'
        if (is_null($jsonOBJ) || !isset($jsonOBJ->id) || !isset($jsonOBJ->nombre) || empty($jsonOBJ->nombre)) {
            $data['message'] = 'Error: JSON inválido o faltan datos (id, nombre).';
        } else {
            // SE OBTIENEN Y ESCAPAN LOS DATOS
            $id = mysqli_real_escape_string($conexion, $jsonOBJ->id);
            $nombre = mysqli_real_escape_string($conexion, $jsonOBJ->nombre);
            $marca = isset($jsonOBJ->marca) ? mysqli_real_escape_string($conexion, $jsonOBJ->marca) : 'NA';
            $modelo = isset($jsonOBJ->modelo) ? mysqli_real_escape_string($conexion, $jsonOBJ->modelo) : 'XX-000';
            $precio = isset($jsonOBJ->precio) ? floatval($jsonOBJ->precio) : 0.0;
            $detalles = isset($jsonOBJ->detalles) ? mysqli_real_escape_string($conexion, $jsonOBJ->detalles) : 'NA';
            $unidades = isset($jsonOBJ->unidades) ? intval($jsonOBJ->unidades) : 1;
            $imagen = isset($jsonOBJ->imagen) ? mysqli_real_escape_string($conexion, $jsonOBJ->imagen) : 'img/default.png';

            // Checamos por duplicados (mismo nombre, PERO DIFERENTE ID)
            $sql_check = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND id != {$id} AND eliminado = 0";
            
            if ($result_check = $conexion->query($sql_check)) {

                if ($result_check->num_rows == 0) {
                    // No hay duplicados, se puede actualizar
                    $conexion->set_charset("utf8");
                    $sql_update = "UPDATE productos SET 
                            nombre = '{$nombre}', 
                            marca = '{$marca}', 
                            modelo = '{$modelo}', 
                            precio = {$precio}, 
                            detalles = '{$detalles}', 
                            unidades = {$unidades}, 
                            imagen = '{$imagen}' 
                        WHERE id = {$id}";

                    if($conexion->query($sql_update)){
                        $data['status'] =  "success";
                        $data['message'] =  "Producto actualizado";
                    } else {
                        $data['message'] = "ERROR: No se pudo ejecutar $sql_update. " . mysqli_error($conexion);
                    }
                } else {
                    // Sí hay un duplicado
                    $data['status'] = 'error';
                    $data['message'] = 'Ya existe OTRO producto con ese nombre';
                }
                $result_check->free();

            } else {
                $data['message'] = "ERROR: No se pudo ejecutar $sql_check. " . mysqli_error($conexion);
            }
        }
        $conexion->close();
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    header('Content-Type: application/json'); // Aseguramos el header
    echo json_encode($data, JSON_PRETTY_PRINT);
?>