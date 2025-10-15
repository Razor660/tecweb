<?php
// get_productos_xhtml_v2.php
// Versión corregida para ser compatible con XHTML 1.1

// Configuración de conexión (sin cambios)
$dbHost = '127.0.0.1';
$dbName = 'marketzone';
$dbUser = 'root';
$dbPass = '@Nothing30';

// Conectar con PDO y manejo de errores (sin cambios)
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

// Obtener y validar parámetro tope (sin cambios)
$tope = isset($_GET['tope']) ? filter_var($_GET['tope'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) : false;
if ($tope === false && isset($_GET['tope'])) {
    header('Content-Type: text/plain; charset=utf-8', true, 400);
    echo "Parámetro 'tope' inválido. Debe ser un entero mayor o igual a 0.";
    exit;
}

// Cabeceras XHTML (sin cambios)
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Productos Modificables (unidades ≤ <?php echo $tope !== false ? htmlspecialchars($tope) : '—'; ?>)</title>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <style type="text/css">
    body { font-family: Arial, Helvetica, sans-serif; padding: 1rem; }
    .producto { border:1px solid #ccc; padding: .6rem; margin-bottom: .6rem; }
    .producto img{ max-width:120px; max-height:120px; }
    dl dt{ font-weight:bold; }
  </style>
</head>
<body class="container">
  <h1>Productos (Todos)</h1>
  <p>Esta es la versión que muestra todos los productos (incluyendo los marcados como eliminados) y permite modificarlos.</p>
  
  <?php if ($tope === false): ?>
    <p class="alert alert-warning">Debe indicar el parámetro <code>?tope=NNN</code> para filtrar.</p>
  <?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Precio</th>
                <th>Unidades</th>
                <th>Imagen</th>
                <th>Modificar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen FROM productos WHERE unidades <= :tope ORDER BY id ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':tope', $tope, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                echo '<tr><td colspan="7">No se encontraron productos con unidades ≤ ' . htmlspecialchars($tope) . '.</td></tr>';
            } else {
                while ($p = $stmt->fetch()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($p['nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['marca']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['modelo']) . '</td>';
                    echo '<td>$' . htmlspecialchars($p['precio']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['unidades']) . '</td>';
                    // CORREGIDO: Se autocierra la etiqueta <img>
                    echo '<td><img src="' . htmlspecialchars($p['imagen'] ?? '') . '" width="50" alt="Imagen de ' . htmlspecialchars($p['nombre']) . '" /></td>';
                    
                    echo '<td>';
                    echo '<form action="formulario_productos_v2.php" method="POST">';
                    // CORREGIDO: Se autocierran las etiquetas <input>
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($p['id']) . '" />';
                    echo '<input type="hidden" name="nombre" value="' . htmlspecialchars($p['nombre']) . '" />';
                    echo '<input type="hidden" name="marca" value="' . htmlspecialchars($p['marca']) . '" />';
                    echo '<input type="hidden" name="modelo" value="' . htmlspecialchars($p['modelo']) . '" />';
                    echo '<input type="hidden" name="precio" value="' . htmlspecialchars($p['precio']) . '" />';
                    echo '<input type="hidden" name="detalles" value="' . htmlspecialchars($p['detalles'] ?? '') . '" />';
                    echo '<input type="hidden" name="unidades" value="' . htmlspecialchars($p['unidades']) . '" />';
                    echo '<input type="hidden" name="imagen" value="' . htmlspecialchars($p['imagen'] ?? '') . '" />';
                    echo '<input type="submit" value="Modificar" class="btn btn-warning btn-sm" />';
                    echo '</form>';
                    echo '</td>';
                    
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
  <?php endif; ?>
</body>
</html>