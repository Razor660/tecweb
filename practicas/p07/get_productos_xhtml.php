<?php
// get_productos_xhtml.php
// Muestra productos con unidades <= ?tope=NNN en formato XHTML
// Versión adaptada a la definición SQL proporcionada (productos table)

// Configuración de conexión
$dbHost = '127.0.0.1';
$dbName = 'marketzone';
$dbUser = 'root';
$dbPass = '@Nothing30';

// Conectar con PDO y manejo de errores
try {
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // En producción no mostrar detalles sensibles
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    exit;
}

// Obtener y validar parámetro tope (debe ser entero no negativo)
$tope = isset($_GET['tope']) ? filter_var($_GET['tope'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) : false;
if ($tope === false && isset($_GET['tope'])) {
    // valor inválido
    header('Content-Type: text/plain; charset=utf-8', true, 400);
    echo "Parámetro 'tope' inválido. Debe ser un entero mayor o igual a 0.";
    exit;
}

// Cabeceras XHTML
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Productos (unidades ≤ <?php echo $tope !== false && $tope !== null ? htmlspecialchars($tope, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '—'; ?>)</title>
  <style type="text/css">
    body { font-family: Arial, Helvetica, sans-serif; padding: 1rem; }
    .producto { border:1px solid #ccc; padding: .6rem; margin-bottom: .6rem; display:flex; gap: .6rem; align-items:flex-start; }
    .producto img{ max-width:120px; max-height:120px; object-fit:contain; }
    .meta { flex:1; }
    dl dt{ font-weight:bold; float:left; width:8rem; clear:left; }
    dl dd{ margin:0 0 0.5rem 8.2rem; }
    .placeholder { width:120px; height:120px; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center; color:#888; font-size:0.9rem; }
  </style>
</head>
<body>
  <h1>Productos</h1>

<?php if ($tope === false || $tope === null): ?>
  <p>Debe indicar el parámetro <code>?tope=NNN</code> con un entero no negativo.</p>
<?php else: ?>

<?php
// Consulta con prepared statement
$sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen
        FROM productos
        WHERE unidades <= :tope
        ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':tope', $tope, PDO::PARAM_INT);
$stmt->execute();

while ($p = $stmt->fetch()) {
    // Normalizar y sanitizar campos según la definición SQL:
    // id: BIGINT unsigned -> tratar como entero (string-safe)
    $id = isset($p['id']) ? $p['id'] : '';
    // nombre, marca, modelo: NOT NULL per schema
    $nombre = isset($p['nombre']) ? $p['nombre'] : '';
    $marca = isset($p['marca']) ? $p['marca'] : '';
    $modelo = isset($p['modelo']) ? $p['modelo'] : '';
    // precio: DOUBLE(10,2) -> formato con 2 decimales
    $precio = isset($p['precio']) ? number_format((float)$p['precio'], 2, '.', ',') : '0.00';
    // detalles: VARCHAR(250) DEFAULT NULL
    $detalles = isset($p['detalles']) && $p['detalles'] !== null ? $p['detalles'] : '';
    // unidades: INT NOT NULL DEFAULT 0
    $unidades = isset($p['unidades']) ? intval($p['unidades']) : 0;
    // imagen: VARCHAR(100) DEFAULT NULL -> puede ser ruta relativa o NULL
    $imagen = isset($p['imagen']) && $p['imagen'] !== null ? $p['imagen'] : null;

    // Preparar etiqueta de imagen de forma segura
    $imgTag = '';
    if (!empty($imagen)) {
        // Solo usar rutas dentro del directorio actual o subdirectorios para evitar fugas.
        $candidate = __DIR__ . '/' . ltrim($imagen, '/\\');
        if (file_exists($candidate) && is_file($candidate)) {
            $imgUrl = htmlspecialchars($imagen, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $imgAlt = htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $imgTag = '<img src="' . $imgUrl . '" alt="' . $imgAlt . '" />';
        } else {
            $imgTag = '<div class="placeholder">sin imagen</div>';
        }
    } else {
        $imgTag = '<div class="placeholder">sin imagen</div>';
    }

    // Imprimir producto (XHTML-safe)
    echo '<div class="producto">';
    echo $imgTag;
    echo '<div class="meta">';
    echo '<h2>' . htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' <small>(ID ' . htmlspecialchars((string)$id, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ')</small></h2>';
    if ($detalles !== '') {
        echo '<p>' . nl2br(htmlspecialchars($detalles, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) . '</p>';
    } else {
        echo '<p><em>Sin detalles</em></p>';
    }
    echo '<dl>';
    echo '<dt>Marca</dt><dd>' . htmlspecialchars($marca, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</dd>';
    echo '<dt>Modelo</dt><dd>' . htmlspecialchars($modelo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</dd>';
    echo '<dt>Precio</dt><dd>$' . $precio . '</dd>';
    echo '<dt>Unidades</dt><dd>' . htmlspecialchars((string)$unidades, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</dd>';
    echo '</dl>';
    echo '</div></div>';
}

if ($stmt->rowCount() === 0) {
    echo '<p>No se encontraron productos con unidades ≤ ' . htmlspecialchars((string)$tope, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '.</p>';
}
?>

<?php endif; ?>

  <p><a href="get_productos_xhtml.php?tope=700">Prueba con tope=700</a></p>
</body>
</html>