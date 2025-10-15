<?php

function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

$id = e($_POST['id']);
$nombre = e($_POST['nombre']);
$marca = e($_POST['marca']);
$modelo = e($_POST['modelo']);
$precio = e($_POST['precio']);
$detalles = e($_POST['detalles']);
$unidades = e($_POST['unidades']);
$imagen = e($_POST['imagen']);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar Smartphone</title>
    <style>
    </style>
    <script>
        function validarFormulario() {
            // ... Lógica de validación ...
            return true;
        }
    </script>
</head>
<body>
  <h1>Editar Smartphone</h1>
  <form name="edicionProducto" action="update_producto.php" method="post" onsubmit="return validarFormulario();">
    
    <input type="hidden" name="id" value="<?= $id ?>">
    
    <p>Nombre: <input type="text" name="nombre" maxlength="100" value="<?= $nombre ?>"></p>
    
    <p>Marca: 
        <select name="marca">
            <option value="">-- Seleccione una marca --</option>
            <option value="Apple" <?= $marca == 'Apple' ? 'selected' : '' ?>>Apple</option>
            <option value="Samsung" <?= $marca == 'Samsung' ? 'selected' : '' ?>>Samsung</option>
            <option value="Google" <?= $marca == 'Google' ? 'selected' : '' ?>>Google</option>
            <option value="Xiaomi" <?= $marca == 'Xiaomi' ? 'selected' : '' ?>>Xiaomi</option>
            <option value="Huawei" <?= $marca == 'Huawei' ? 'selected' : '' ?>>Huawei</option>
            <option value="Motorola" <?= $marca == 'Motorola' ? 'selected' : '' ?>>Motorola</option>
        </select>
    </p>

    <p>Modelo: <input type="text" name="modelo" maxlength="25" value="<?= $modelo ?>"></p>
    <p>Precio: <input type="number" step="0.01" name="precio" value="<?= $precio ?>"></p>
    <p>Detalles: <input type="text" name="detalles" maxlength="250" value="<?= $detalles ?>"></p>
    <p>Unidades: <input type="number" name="unidades" min="0" value="<?= $unidades ?>"></p>
    <p>Imagen (ruta relativa): <input type="text" name="imagen" placeholder="img/miimagen.png" value="<?= $imagen ?>"></p> 
    <p><input type="submit" value="Actualizar Producto"></p>
  </form>
</body>
</html>