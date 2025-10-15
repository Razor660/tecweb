<?php
// Su único objetivo es recibir datos y mostrarlos en un formulario.

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
    <title>Vista Previa para Edición (v2)</title>
    </head>
<body>
  <h1>Datos del Producto a Modificar</h1>
  <p>Este formulario solo muestra los datos recibidos.</p>
  
  <form name="vistaProducto" action="#" method="post">
    <input type="hidden" name="id" value="<?= $id ?>" />
    
    <p>Nombre: <input type="text" name="nombre" value="<?= $nombre ?>" /></p>
    <p>Marca: <input type="text" name="marca" value="<?= $marca ?>" /></p>
    <p>Modelo: <input type="text" name="modelo" value="<?= $modelo ?>" /></p>
    <p>Precio: <input type="number" step="0.01" name="precio" value="<?= $precio ?>" /></p>
    <p>Detalles: <input type="text" name="detalles" value="<?= $detalles ?>" /></p>
    <p>Unidades: <input type="number" name="unidades" value="<?= $unidades ?>" /></p>
    <p>Imagen: <input type="text" name="imagen" value="<?= $imagen ?>" /></p> 
    <p><input type="submit" value="Enviar (No funcional aún)" disabled /></p>
  </form>
</body>
</html>