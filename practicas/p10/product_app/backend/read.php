<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array(); // Este será nuestro array de productos

    // SE VERIFICA HABER RECIBIDO EL TÉRMINO DE BÚSQUEDA
    // Usamos 'id' porque es el parámetro que envía el frontend (app.js)
    if( isset($_POST['id']) ) {
        $search_term = $_POST['id'];

        // SE REALIZA LA QUERY DE BÚSQUEDA CON LIKE
        $sql = "SELECT * FROM productos WHERE 
                    nombre LIKE '%{$search_term}%' OR 
                    marca LIKE '%{$search_term}%' OR 
                    detalles LIKE '%{$search_term}%'";
        
        if ( $result = $conexion->query($sql) ) {
            
            // SE OBTIENEN LOS RESULTADOS FILA POR FILA
			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                
                // Se mapean los datos de cada fila
                $product_data = array();
                foreach($row as $key => $value) {
                    $product_data[$key] = $value; // utf8_encode($value);
                }
                // Se agrega la fila al array de respuesta
                $data[] = $product_data;
            }
			$result->free();
		} else {
            die('Query Error: '.mysqli_error($conexion));
        }
		$conexion->close();
    } 
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>