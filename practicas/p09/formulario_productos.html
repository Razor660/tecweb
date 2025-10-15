<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alta de Producto (Smartphone)</title>
    <style>
        body { font-family: sans-serif; }
        form { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; }
        p { margin-bottom: 15px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        input[type="submit"] { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
    </style>
    <script>
        function validarFormulario() {
            // Obtener los valores de los campos del formulario
            var nombre = document.forms["registroProducto"]["nombre"].value;
            var marca = document.forms["registroProducto"]["marca"].value;
            var modelo = document.forms["registroProducto"]["modelo"].value;
            var precio = parseFloat(document.forms["registroProducto"]["precio"].value);
            var detalles = document.forms["registroProducto"]["detalles"].value;
            var unidades = parseInt(document.forms["registroProducto"]["unidades"].value);
            var imagenInput = document.forms["registroProducto"]["imagen"];

            // a. Validación del Nombre
            if (nombre.trim() === "" || nombre.length > 100) {
                alert("El nombre es requerido y no puede exceder los 100 caracteres.");
                return false; // Detiene el envío del formulario
            }

            // b. Validación de la Marca
            if (marca === "") {
                alert("Debe seleccionar una marca.");
                return false;
            }

            // c. Validación del Modelo
            const modeloRegex = /^[a-zA-Z0-9\s-]{1,25}$/;
            if (modelo.trim() === "" || !modeloRegex.test(modelo)) {
                alert("El modelo es requerido, debe ser alfanumérico y no exceder los 25 caracteres.");
                return false;
            }

            // d. Validación del Precio
            if (isNaN(precio) || precio <= 99.99) {
                alert("El precio es requerido y debe ser mayor a 99.99.");
                return false;
            }

            // e. Validación de Detalles
            if (detalles.length > 250) {
                alert("Los detalles no pueden exceder los 250 caracteres.");
                return false;
            }

            // f. Validación de Unidades
            if (isNaN(unidades) || unidades < 0) {
                alert("Las unidades son requeridas y deben ser un número mayor o igual a 0.");
                return false;
            }
            
            // g. Asignar imagen por defecto si el campo está vacío
            if (imagenInput.value.trim() === "") {
                imagenInput.value = "img/unnamed.png";
            }

            // Si todas las validaciones pasan, el formulario se envía
            return true;
        }
    </script>
</head>
<body>
  <h1>Registrar Nuevo Smartphone</h1>
  <form name="registroProducto" action="set_producto_v2.php" method="post" onsubmit="return validarFormulario();">
    <p>Nombre: <input type="text" name="nombre" maxlength="100"></p>
    
    <p>Marca: 
        <select name="marca">
            <option value="">-- Seleccione una marca --</option>
            <option value="Apple">Apple</option>
            <option value="Samsung">Samsung</option>
            <option value="Google">Google</option>
            <option value="Xiaomi">Xiaomi</option>
            <option value="Huawei">Huawei</option>
            <option value="Motorola">Motorola</option>
        </select>
    </p>

    <p>Modelo: <input type="text" name="modelo" maxlength="25"></p>
    <p>Precio: <input type="number" step="0.01" name="precio"></p>
    <p>Detalles: <input type="text" name="detalles" maxlength="250"></p>
    <p>Unidades: <input type="number" name="unidades" min="0"></p>
    <p>Imagen (ruta relativa): <input type="text" name="imagen" placeholder="img/miimagen.png"></p> 
    <p><input type="submit" value="Registrar"></p>
  </form>
</body>
</html>