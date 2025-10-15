<?php
header('Content-Type: text/html; charset=utf-8');

// --- Lógica de conexión a la BD ---
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
    echo "<h1>Error de conexión</h1><p>No se pudo conectar a la base de datos: {$e->getMessage()}</p>";
    exit;
}

// --- Recibir y validar los datos del formulario ---
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    echo "<h1>Error</h1><p>El ID del producto no es válido.</p>";
    exit;
}
$nombre = trim($_POST['nombre'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$detalles = trim($_POST['detalles'] ?? '');
$unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
$imagen = trim($_POST['imagen'] ?? '');

// --- Preparar y ejecutar la sentencia SQL UPDATE ---
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
            if ($stmt->rowCount() > 0) {
                echo "<h1 class='text-success'>Registro actualizado exitosamente</h1>";
                echo "<p>El producto con ID <strong>{$id}</strong> ha sido modificado.</p>";
            } else {
                echo "<h1 class='text-info'>Sin cambios</h1>";
                echo "<p>No se realizaron modificaciones para el producto con ID <strong>{$id}</strong>.</p>";
            }
        } else {
            echo "<h1 class='text-danger'>Error al actualizar</h1>";
            echo "<p>Ocurrió un error y no se pudo actualizar el registro.</p>";
        }
        ?>
        <hr>
        <h2>Navegación</h2>
        <p><a href="get_productos_vigentes_v2.php" class="btn btn-primary">Ver Productos Vigentes</a></p>
        <p><a href="get_productos_xhtml_v2.php?tope=1000" class="btn btn-secondary">Ver Todos los Productos (XHTML)</a></p>
    </div>
</body>
</html>