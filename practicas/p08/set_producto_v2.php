<?php
// set_producto_v2.php
// Recibe POST del formulario y guarda el producto si no existe (nombre+marca+modelo)

$host = '127.0.0.1';
$db   = 'marketzone';
$user = 'root';         
$pass = '@Nothing30';    

// Conexión
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Error de conexión con la base de datos: " . $mysqli->connect_error;
    exit;
}

// obtener y sanitizar POST
$nombre  = trim($_POST['nombre']  ?? '');
$marca   = trim($_POST['marca']   ?? '');
$modelo  = trim($_POST['modelo']  ?? '');
$precio  = trim($_POST['precio']  ?? '0');
$detalles= trim($_POST['detalles']?? '');
$unidades= intval($_POST['unidades'] ?? 0);
$imagen  = trim($_POST['imagen']  ?? null); // puede ser ruta relativa o NULL

// validaciones básicas
$errors = [];
if ($nombre === '')  $errors[] = "El nombre es obligatorio.";
if ($marca === '')   $errors[] = "La marca es obligatoria.";
if ($modelo === '')  $errors[] = "El modelo es obligatorio.";
// checa longitudes según definición de la tabla
if (strlen($nombre) > 100)  $errors[] = "El nombre excede 100 caracteres.";
if (strlen($marca) > 25)    $errors[] = "La marca excede 25 caracteres.";
if (strlen($modelo) > 25)   $errors[] = "El modelo excede 25 caracteres.";
if (!is_numeric($precio))   $errors[] = "Precio inválido.";

// si hay errores, muestro XHTML con mensajes
if (!empty($errors)) {
    header('Content-Type: application/xhtml+xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
      "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Error</title></head>
    <body>
      <h1>Errores en envío</h1>
      <ul>
      <?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . '</li>'; ?>
      </ul>
      <p><a href="formulario_productos.html">Volver</a></p>
    </body></html>
    <?php
    exit;
}

// Verificar duplicado: nombre + marca + modelo
$sql_check = "SELECT COUNT(*) as cnt FROM productos WHERE nombre = ? AND marca = ? AND modelo = ?";
$stmt = $mysqli->prepare($sql_check);
$stmt->bind_param('sss', $nombre, $marca, $modelo);
$stmt->execute();
$r = $stmt->get_result()->fetch_assoc();
if ($r['cnt'] > 0) {
    header('Content-Type: application/xhtml+xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
      "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Duplicado</title></head>
    <body>
      <h1>Producto duplicado</h1>
      <p>Ya existe un prod  ucto con el mismo nombre, marca y modelo.</p>
      <p><a href="formulario_productos.html">Volver</a></p>
    </body></html>
    <?php
    exit;
}

// INSERT usando column names (no id ni eliminado)
// Nota: la columna eliminado tiene DEFAULT 0, por eso queda fuera
$sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt2 = $mysqli->prepare($sql_insert);
$precio_float = floatval($precio);
$img_val = $imagen === '' ? null : $imagen;
$stmt2->bind_param('sssdsis', $nombre, $marca, $modelo, $precio_float, $detalles, $unidades, $img_val);

if ($stmt2->execute()) {
    $new_id = $stmt2->insert_id;
    // Mostrar resumen XHTML de éxito
    header('Content-Type: application/xhtml+xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
      "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Producto creado</title></head>
    <body>
      <h1>Producto insertado correctamente</h1>
      <dl>
        <dt>ID</dt><dd><?php echo htmlspecialchars($new_id); ?></dd>
        <dt>Nombre</dt><dd><?php echo htmlspecialchars($nombre); ?></dd>
        <dt>Marca</dt><dd><?php echo htmlspecialchars($marca); ?></dd>
        <dt>Modelo</dt><dd><?php echo htmlspecialchars($modelo); ?></dd>
        <dt>Precio</dt><dd><?php echo number_format($precio_float, 2); ?></dd>
        <dt>Detalles</dt><dd><?php echo nl2br(htmlspecialchars($detalles)); ?></dd>
        <dt>Unidades</dt><dd><?php echo htmlspecialchars($unidades); ?></dd>
        <dt>Imagen</dt><dd><?php echo htmlspecialchars($img_val); ?></dd>
      </dl>
      <p><a href="formulario_productos.html">Registrar otro producto</a></p>
    </body></html>
    <?php
} else {
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Error al insertar: " . $mysqli->error;
}
$mysqli->close();
