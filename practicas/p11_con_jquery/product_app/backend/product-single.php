<?php
    include_once __DIR__.'/database.php';

    // SE VERIFICA HABER RECIBIDO EL ID
    if( isset($_GET['id']) ) {
        $id = $_GET['id'];
        
        // Evitar inyección SQL simple
        $id_escapado = mysqli_real_escape_string($conexion, $id);
        
        $sql = "SELECT * FROM productos WHERE id = {$id_escapado}";
        
        if ( $result = $conexion->query($sql) ) {
            // SE OBTIENE EL PRODUCTO (DEBERÍA SER SOLO UNO)
            $row = $result->fetch_assoc();

            if(!is_null($row)) {
                $data = array();
                // SE CODIFICAN A UTF-8 LOS DATOS
                foreach($row as $key => $value) {
                    $data[$key] = utf8_encode($value);
                }
                // SE HACE LA CONVERSIÓN DE ARRAY A JSON (OBJETO ÚNICO)
                header('Content-Type: application/json');
                echo json_encode($data, JSON_PRETTY_PRINT);
            } else {
                echo json_encode(array('message' => 'No se encontró el producto.'));
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($conexion));
        }
        $conexion->close();
    } else {
         echo json_encode(array('message' => 'No se recibió ID.'));
    }
?>