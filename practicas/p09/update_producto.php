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
    echo "<h1>Error de conexión</h1><p>{$e->getMessage()}</p>";
    exit;
}

// Recibir y validar datos del formulario
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<h1>Error</h1><p>ID de producto inválido.</p>";
    exit;
}

// Sanitizar el resto de los datos
$nombre = trim($_POST['nombre'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$detalles = trim($_POST['detalles'] ?? '');
$unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
$imagen = trim($_POST['imagen'] ?? '');

// Preparar la sentencia SQL UPDATE
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

// Bind de los parámetros
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

// Ejecutar y mostrar resultado
echo '<!DOCTYPE html><html><head><title>Resultado de Actualización</title></head><body>';
if ($stmt->execute($params)) {
    // rowCount() devuelve el número de filas afectadas. Si es > 0, algo cambió.
    if ($stmt->rowCount() > 0) {
        echo "<h1>Registro actualizado exitosamente</h1>";
        echo "<p>El producto con ID {$id} ha sido modificado.</p>";
    } else {
        echo "<h1>Sin cambios</h1>";
        echo "<p>No se realizaron cambios en el producto con ID {$id} (los datos eran los mismos).</p>";
    }
} else {
    echo "<h1>Error al actualizar</h1>";
    echo "<p>No se pudo ejecutar la actualización.</p>";
}

// Hipervínculos solicitados
echo '<h2>Navegación</h2>';
echo '<p><a href="get_productos_vigentes_v2.php">Ver Productos Vigentes</a></p>';
echo '</body></html>';
<?php
// update_producto.php
header('Content-Type: text/html; charset=utf-8');

// --- Lógica de conexión a la BD (igual que en los otros scripts) ---
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
    echo "<h1>Error de conexión</h1><p>{$e->getMessage()}</p>";
    exit;
}

// Recibir y validar datos del formulario
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<h1>Error</h1><p>ID de producto inválido.</p>";
    exit;
}

// Sanitizar el resto de los datos
$nombre = trim($_POST['nombre'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$detalles = trim($_POST['detalles'] ?? '');
$unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
$imagen = trim($_POST['imagen'] ?? '');

// Preparar la sentencia SQL UPDATE
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

// Bind de los parámetros
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

// Ejecutar y mostrar resultado
echo '<!DOCTYPE html><html><head><title>Resultado de Actualización</title></head><body>';
if ($stmt->execute($params)) {
    // rowCount() devuelve el número de filas afectadas. Si es > 0, algo cambió.
    if ($stmt->rowCount() > 0) {
        echo "<h1>Registro actualizado exitosamente</h1>";
        echo "<p>El producto con ID {$id} ha sido modificado.</p>";
    } else {
        echo "<h1>Sin cambios</h1>";
        echo "<p>No se realizaron cambios en el producto con ID {$id} (los datos eran los mismos).</p>";
    }
} else {
    echo "<h1>Error al actualizar</h1>";
    echo "<p>No se pudo ejecutar la actualización.</p>";
}

// Hipervínculos solicitados
echo '<h2>Navegación</h2>';
echo '<p><a href="get_productos_vigentes_v2.php">Ver Productos Vigentes</a></p>';
echo '<p><a href="get_productos_xhtml_v2.php">Ver Todos los Productos (XHTML)</a></p>';

echo '</body></html>';
?>
?>