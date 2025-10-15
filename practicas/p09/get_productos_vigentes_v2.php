<?php
// get_productos_vigentes_v2.php
// Versión corregida para ser compatible con XHTML 1.1

// --- (LÓGICA DE CONEXIÓN A LA BD - Igual que antes) ---
$dbHost = '127.0.0.1';
$dbName = 'marketzone';
$dbUser = 'root';
$dbPass = '@Nothing30';

try {
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Error al conectar: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Productos Vigentes (Modificable)</title>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head>
<body class="container">
  <h1 class="mt-4">Productos Vigentes (Modificable)</h1>
  <table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Precio</th>
            <th>Unidades</th>
            <th>Detalles</th>
            <th>Imagen</th>
            <th>Modificar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql = "SELECT * FROM productos WHERE eliminado = 0 ORDER BY id ASC";
            $stmt = $pdo->query($sql);
            while ($p = $stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($p['id']) . '</td>';
                echo '<td>' . htmlspecialchars($p['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($p['marca']) . '</td>';
                echo '<td>' . htmlspecialchars($p['modelo']) . '</td>';
                echo '<td>$' . htmlspecialchars($p['precio']) . '</td>';
                echo '<td>' . htmlspecialchars($p['unidades']) . '</td>';
                echo '<td>' . htmlspecialchars($p['detalles'] ?? '') . '</td>';
                // CORREGIDO: Se autocierra la etiqueta <img>
                echo '<td><img src="' . htmlspecialchars($p['imagen'] ?? '') . '" width="50" alt="Imagen de producto" /></td>';
                
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
                echo '<input type="submit" value="Modificar" class="btn btn-warning" />';
                echo '</form>';
                echo '</td>';

                echo '</tr>';
            }
        ?>
    </tbody>
  </table>
</body>
</html>