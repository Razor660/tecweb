<?php
header('Content-Type: text/html; charset=utf-8');

$dbHost = '127.0.0.1';
$dbName = 'marketzone';
$dbUser = 'root';
$dbPass = '@Nothing30'; 

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Si hay un error de conexión, muestra un mensaje y termina el script.
    echo "<h1>Error de conexión</h1><p>No se pudo conectar a la base de datos: {$e->getMessage()}</p>";
    exit;
}

// --- 2. Recibir y validar los datos del formulario ---
// Es crucial validar el ID para asegurar que es un número entero.
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    echo "<h1>Error</h1><p>El ID del producto no es válido.</p>";
    exit;
}

// Recibimos el resto de los datos del formulario.
$nombre = trim($_POST['nombre'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$detalles = trim($_POST['detalles'] ?? '');
$unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
$imagen = trim($_POST['imagen'] ?? '');

// --- 3. Preparar y ejecutar la sentencia SQL UPDATE ---
// Usamos una sentencia preparada para prevenir inyección SQL.
$sql = "UPDATE productos 
        SET nombre = :nombre, 
            marca = :marca, 
            modelo = :modelo, 
            precio = :precio, 
            detalles = :detalles, 
            unidades = :unidades, 
            imagen = :imagen 
        WHERE id = :id";

$stmt = $pdo->prepare($sql);

// Creamos un array con los parámetros para vincularlos a la consulta.
$params = [
    ':nombre' => $nombre,
    ':marca' => $marca,
    ':modelo' => $modelo,
    ':precio' => $precio,
    ':detalles' => $detalles,
    ':unidades' => $unidades,
    ':imagen' => $imagen,
    ':id' => $id
];

// --- 4. Mostrar el resultado de la operación ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de Actualización</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style> body { padding: 20px; } </style>
</head>
<body>
    <div class="container">
        <?php
        if ($stmt->execute($params)) {
            // rowCount() nos dice cuántas filas fueron afectadas.
            if ($stmt->rowCount() > 0) {
                echo "<h1 class='text-success'>Registro actualizado exitosamente</h1>";
                echo "<p>El producto con ID <strong>{$id}</strong> ha sido modificado en la base de datos.</p>";
            } else {
                echo "<h1 class='text-info'>Sin cambios</h1>";
                echo "<p>No se realizaron modificaciones en el producto con ID <strong>{$id}</strong>, probablemente porque los datos enviados eran los mismos que ya existían.</p>";
            }
        } else {
            echo "<h1 class='text-danger'>Error al actualizar</h1>";
            echo "<p>Ocurrió un error y no se pudo actualizar el registro.</p>";
        }
        ?>

        <hr>
        <h2>Navegación</h2>
        <p><a href="get_productos_vigentes_v2.php" class="btn btn-primary">Ver Productos Vigentes</a></p>
        <p><a href="get_productos_xhtml_v2.php?tope=1000" class="btn btn-secondary">Ver Todos los Productos (XHTML con tope)</a></p>
    </div>
</body>
</html>