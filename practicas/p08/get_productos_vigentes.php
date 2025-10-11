<?php
// get_productos_vigentes.php
// Muestra productos vigentes (eliminado = 0) con unidades <= ?tope=NNN en formato XHTML
// Basado en get_productos_xhtml.php de la práctica anterior

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
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    exit;
}

// Obtener y validar parámetro ?tope (opcional)
$tope = isset($_GET['tope'])
    ? filter_var($_GET['tope'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]])
    : false;

if ($tope === false && isset($_GET['tope'])) {
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
  <title>Productos vigentes</title>
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
  <h1>Productos vigentes (no eliminados)</h1>

<?php
// ==== CAMBIO PRINCIPAL: filtramos solo productos con eliminado = 0 ====
if ($tope === false || $tope === null) {
    $sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen
            FROM productos
            WHERE eliminado = 0
            ORDER BY id ASC";
    $stmt = $pdo->query($sql);
} else {
    $sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen
            FROM productos
            WHERE eliminado = 0 AND unidades <= :tope
            ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tope', $tope, PDO::PARAM_INT);
    $stmt->execute();
}

// ==== FIN DEL CAMBIO PRINCIPAL ====

// Imprimir resultados
$productos = $stmt->fetchAll();

if (count($productos) === 0) {
    echo '<p>No se encontraron productos vigentes';
    if ($tope !== false && $tope !== null) {
        echo ' con unidades ≤ ' . htmlspecialchars((string)$tope, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
    echo '.</p>';
} else {
    foreach ($productos as $p) {
        $id = $p['id'] ?? '';
        $nombre = $p['nombre'] ?? '';
        $marca = $p['marca'] ?? '';
        $modelo = $p['modelo'] ?? '';
        $precio = isset($p['precio']) ? number_format((float)$p['precio'], 2, '.', ',') : '0.00';
        $detalles = $p['detalles'] ?? '';
        $unidades = (int)($p['unidades'] ?? 0);
        $imagen = $p['imagen'] ?? null;

        // Imagen segura
        $imgTag = '';
        if (!empty($imagen)) {
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

        // Renderizar producto
        echo '<div class="producto">';
        echo $imgTag;
        echo '<div class="meta">';
        echo '<h2>' . htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') .
             ' <small>(ID ' . htmlspecialchars((string)$id, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ')</small></h2>';
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
}
?>
  <p><a href="get_productos_vigentes.php?tope=100">Prueba con tope=100</a></p>
</body>
</html>
