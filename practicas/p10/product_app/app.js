document.addEventListener('DOMContentLoaded', function() {
    
    // Asigna el evento 'submit' al formulario de búsqueda
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita que la página se recargue
            buscarProducto();
        });
    }

    // Asigna el evento 'submit' al formulario para agregar productos
    const productForm = document.getElementById('product-form');
    if (productForm) {
        productForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita que la página se recargue
            agregarProducto();
        });
    }
});

/**
 * Función para buscar productos.
 * Realiza una petición AJAX con el método GET al script `read.php` y
 * muestra los resultados en la tabla.
 */
function buscarProducto() {
    // ... (Esta función se mantiene igual que en la respuesta anterior)
    const searchTerm = document.getElementById('search-input').value.trim();
    const xhr = new XMLHttpRequest();
    const url = `backend/read.php?search=${encodeURIComponent(searchTerm)}`;
    xhr.open('GET', url, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const productos = JSON.parse(xhr.responseText);
                let template = '';
                
                if (productos.length > 0) {
                    productos.forEach(producto => {
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td>${producto.nombre}</td>
                                <td>${producto.marca}</td>
                                <td>${producto.modelo}</td>
                                <td>$${producto.precio}</td>
                                <td>${producto.detalles}</td>
                                <td>${producto.unidades}</td>
                            </tr>
                        `;
                    });
                } else {
                    template = '<tr><td colspan="7">No se encontraron productos.</td></tr>';
                }
                document.getElementById('tabla-resultados').innerHTML = template;
            } catch (error) {
                console.error("Error al procesar la respuesta JSON:", error);
                alert("Ocurrió un error al recibir los datos del servidor.");
            }
        }
    };
    xhr.send();
}

/**
 * Función para agregar un nuevo producto.
 * Realiza una validación DETALLADA de los datos, los empaqueta en JSON y los envía
 * con el método POST al script `create.php`.
 */
function agregarProducto() {
    console.log("El botón de agregar fue presionado. Iniciando validaciones...");
    

    // 1. Recolecta los datos del formulario
    const nombre = document.getElementById('nombre').value.trim();
    const marca = document.getElementById('marca').value.trim();
    const modelo = document.getElementById('modelo').value.trim();
    const precio = document.getElementById('precio').value.trim();
    const detalles = document.getElementById('detalles').value.trim();
    const unidades = document.getElementById('unidades').value.trim();

    // 2. APLICA TUS VALIDACIONES ESPECÍFICAS
    if (nombre === "" || nombre.length > 100) {
        alert("Error en Nombre: El nombre es obligatorio y debe tener 100 caracteres o menos.");
        return; // Detiene la función
    }
    
    if (marca === "") {
        alert("Error en Marca: La marca es obligatoria.");
        return;
    }

    // Suponiendo que 'modelo' debe ser alfanumérico
    const modeloRegex = /^[a-zA-Z0-9\s-]+$/;
    if (modelo === "" || modelo.length > 25 || !modeloRegex.test(modelo)) {
        alert("Error en Modelo: El modelo es obligatorio, alfanumérico y de 25 caracteres o menos.");
        return;
    }

    const precioVal = parseFloat(precio);
    if (isNaN(precioVal) || precioVal <= 99.99) {
        alert("Error en Precio: El precio es obligatorio y debe ser un número mayor a 99.99.");
        return;
    }

    const unidadesVal = parseInt(unidades, 10);
    if (isNaN(unidadesVal) || unidadesVal < 0) {
        alert("Error en Unidades: Las unidades son obligatorias y deben ser 0 o un número mayor.");
        return;
    }

    if (detalles.length > 250) {
        alert("Error en Detalles: Los detalles no deben tener más de 250 caracteres.");
        return;
    }

    // 3. Si todas las validaciones pasan, crea el objeto de producto
    const producto = {
        nombre: nombre,
        marca: marca,
        modelo: modelo,
        precio: precioVal,
        detalles: detalles,
        unidades: unidadesVal
    };
    
    // 4. Configura y envía la petición AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'backend/create.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(response.message);

            if (response.status === 'success') {
                document.getElementById('product-form').reset();
                buscarProducto(); 
            }
        }
    };

    // 5. Convierte el objeto a JSON y lo envía
    xhr.send(JSON.stringify(producto));
}