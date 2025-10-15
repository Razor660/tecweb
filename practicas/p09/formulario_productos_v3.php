<?php

function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Se reciben los datos del producto vía POST
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
    <title>Editar Smartphone (v3)</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; }
        h1 { text-align: center; }
        form { 
            max-width: 600px; 
            margin: auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 10px; 
            background-color: #fff;
        }
        p { 
            margin-bottom: 15px; 
        }
        input, select { 
            width: 100%; 
            padding: 8px; 
            box-sizing: border-box; 
        }
        input[type="submit"] { 
            background-color: #ffc107; 
            color: black; 
            border: none; 
            cursor: pointer; 
            font-weight: bold;
        }
        input[type="submit"]:hover { 
            background-color: #e0a800; 
        }
    </style>
    <script>
        // La función de validación JS que ya tenía
        function validarFormulario() {
            var nombre = document.forms["edicionProducto"]["nombre"].value;
            var marca = document.forms["edicionProducto"]["marca"].value;
            var modelo = document.forms["edicionProducto"]["modelo"].value;
            var precio = parseFloat(document.forms["edicionProducto"]["precio"].value);
            var detalles = document.forms["edicionProducto"]["detalles"].value;
            var unidades = parseInt(document.forms["edicionProducto"]["unidades"].value);
            var imagenInput = document.forms["edicionProducto"]["imagen"];

            if (nombre.trim() === "" || nombre.length > 100) {
                alert("El nombre es requerido y no puede exceder los 100 caracteres.");
                return false;
            }
            if (marca === "") {
                alert("Debe seleccionar una marca.");
                return false;
            }
            const modeloRegex = /^[a-zA-Z0-9\s-]{1,25}$/;
            if (modelo.trim() === "" || !modeloRegex.test(modelo)) {
                alert("El modelo es requerido, debe ser alfanumérico y no exceder los 25 caracteres.");
                return false;
            }
            if (isNaN(precio) || precio <= 99.99) {
                alert("El precio es requerido y debe ser mayor a 99.99.");
                return false;
            }
            if (detalles.length > 250) {
                alert("Los detalles no pueden exceder los 250 caracteres.");
                return false;
            }
            if (isNaN(unidades) || unidades < 0) {
                alert("Las unidades son requeridas y deben ser un número mayor o igual a 0.");
                return false;
            }
            if (imagenInput.value.trim() === "") {
                imagenInput.value = "img/unnamed.png";
            }
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