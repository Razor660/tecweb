<?php
// get_productos_xhtml.php
// Muestra productos con unidades <= ?tope=NNN en formato XHTML

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
    ]);
} catch (PDOException $e) {
    // En producción no muestres detalles sensibles
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage());
    exit;
}

// Obtener y validar parámetro tope
$tope = isset($_GET['tope']) ? intval($_GET['tope']) : null;

header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Productos (unidades ≤ <?php echo $tope !== null ? htmlspecialchars($tope) : '—'; ?>)</title>
  <style type="text/css">
    body { font-family: Arial, Helvetica, sans-serif; padding: 1rem; }
    .producto { border:1px solid #ccc; padding: .6rem; margin-bottom: .6rem; display:flex; gap: .6rem;}
    .producto img{ max-width:120px; max-height:120px; object-fit:contain; }
    .meta { flex:1; }
    dl dt{ font-weight:bold; }
  </style>
</head>
<body>
  <h1>Productos</h1>

<?php if ($tope === null): ?>
  <p>Debe indicar el parámetro <code>?tope=NNN</code>.</p>
<?php else: ?>

<?php
// Consulta segura con prepared statement
$sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen FROM productos WHERE unidades <= :tope ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':tope' => $tope]);
$productos = $stmt->fetchAll();

if (!$productos) {
    echo '<p>No se encontraron productos con unidades ≤ ' . htmlspecialchars($tope) . '.</p>';
} else {
    foreach ($productos as $p) {
        // Ruta relativa para la imagen (puede ser NULL)
        $imgTag = '';
        if (!empty($p['imagen']) && file_exists(__DIR__ . '/' . $p['imagen'])) {
            $imgUrl = htmlspecialchars($p['imagen']);
            $imgTag = '<img src="' . $imgUrl . '" alt="' . htmlspecialchars($p['nombre']) . '" />';
        } else {
            $imgTag = '<div style="width:120px;height:120px;border:1px dashed #ccc;display:flex;align-items:center;justify-content:center;color:#888">sin imagen</div>';
        }

        echo '<div class="producto">';
        echo $imgTag;
        echo '<div class="meta">';
        echo '<h2>' . htmlspecialchars($p['nombre']) . ' (ID ' . htmlspecialchars($p['id']) . ')</h2>';
        echo '<p>' . nl2br(htmlspecialchars($p['descripcion'])) . '</p>';
        echo '<dl>';
        echo '<dt>Precio</dt><dd>$' . number_format($p['precio'], 2) . '</dd>';
        echo '<dt>Unidades</dt><dd>' . htmlspecialchars($p['unidades']) . '</dd>';
        echo '</dl>';
        echo '</div></div>';
    }
}
?>

<?php endif; ?>

  <p><a href="get_productos_xhtml.php?tope=700">Prueba con tope=700</a></p>
</body>
</html>
