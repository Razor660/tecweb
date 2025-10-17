<?php
    include_once __DIR__.'/database.php';

    // El arreglo que se devolverá en formato JSON
    $products = array();

    // Se verifica haber recibido el parámetro de búsqueda
    if (isset($_POST['search'])) {
        $search = $_POST['search'];

        // Se previene inyección SQL escapando el término de búsqueda
        $search = $conexion->real_escape_string($search);

        // La consulta de búsqueda versátil usando LIKE
        // Busca coincidencias en nombre, marca o detalles
        $query = "SELECT * FROM productos WHERE nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%'";

        if ($result = $conexion->query($query)) {
            // Se itera sobre los resultados y se añaden al arreglo de productos
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $product = array(); // Arreglo para el producto actual
                foreach ($row as $key => $value) {
                    $product[$key] = $value;
                }
                $products[] = $product; // Se añade el producto al arreglo principal
            }
            $result->free();
        } else {
            die('Query Error: ' . mysqli_error($conexion));
        }
        $conexion->close();
    }

    // Se convierte el arreglo de productos a JSON para enviarlo al frontend
    echo json_encode($products, JSON_PRETTY_PRINT);
?>
