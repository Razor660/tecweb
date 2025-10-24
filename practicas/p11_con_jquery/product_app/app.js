// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

function init() {
    var JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
}

// Se encapsula todo el código en $(document).ready()
$(document).ready(function() {
    
    // Variable para rastrear si estamos editando
    let edit = false;

    // Carga el JSON base en el textarea al iniciar
    init();

    // (i) Cargar toda la lista de productos NO eliminados al abrir la página.
    listarProductos();

    // --- MANEJADORES DE EVENTOS JQUERY ---

    /**
     * (ii) y (iii) Cargar tabla y barra de estado al "teclear" en búsqueda.
     */
    $('#search').keyup(function() {
        let search = $(this).val();
        if(search) {
            $.ajax({
                url: './backend/product-search.php',
                type: 'GET',
                data: { search: search },
                success: function(response) {
                    let productos = JSON.parse(response);
                    let template = '';
                    let template_bar = '';

                    if(Object.keys(productos).length > 0) {
                        productos.forEach(producto => {
                            template_bar += `<li>${producto.nombre}</li>`;

                            let descripcion = '';
                            descripcion += '<li>precio: '+producto.precio+'</li>';
                            descripcion += '<li>unidades: '+producto.unidades+'</li>';
                            descripcion += '<li>modelo: '+producto.modelo+'</li>';
                            descripcion += '<li>marca: '+producto.marca+'</li>';
                            descripcion += '<li>detalles: '+producto.detalles+'</li>';
                        
                            template += `
                                <tr productId="${producto.id}">
                                    <td>${producto.id}</td>
                                    <td>${producto.nombre}</td>
                                    <td><ul>${descripcion}</ul></td>
                                    <td>
                                        <button class="product-edit btn btn-warning mr-2">
                                            Editar
                                        </button>
                                        <button class="product-delete btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#product-result').removeClass('d-none').addClass('d-block');
                        $('#container').html(template_bar);
                    } else {
                         $('#product-result').removeClass('d-block').addClass('d-none');
                         template = `<tr><td colspan="4">No se encontraron productos.</td></tr>`;
                    }
                    $('#products').html(template);
                }
            });
        } else {
            $('#product-result').removeClass('d-block').addClass('d-none');
            listarProductos();
        }
    });

    /**
     * (iv) y (v) Registrar o ACTUALIZAR un producto.
     * Este handler ahora decide si agregar o editar basado en la variable 'edit'.
     */
    $('#product-form').submit(function(e) {
        e.preventDefault();

        // 1. Obtenemos los datos del formulario
        var productoJsonString = $('#description').val();
        var finalJSON = JSON.parse(productoJsonString);
        finalJSON['nombre'] = $('#name').val();
        
        // 2. Decidimos la URL (Agregar o Actualizar)
        let url = '';
        if(edit) {
            finalJSON['id'] = $('#productId').val(); // Añadimos el ID para el script de update
            url = './backend/product-update.php';
        } else {
            url = './backend/product-add.php';
        }
        
        // 3. Convertimos a String para enviar
        productoJsonString = JSON.stringify(finalJSON, null, 2);

        // 4. Enviamos por AJAX
        $.ajax({
            url: url,
            type: 'POST',
            data: productoJsonString,
            contentType: 'application/json;charset=UTF-8',
            dataType: 'json', // Especificamos que esperamos un JSON de vuelta
            
            // Callback si el servidor responde HTTP 200
            success: function(respuesta) {
                // (iv) Mostramos el mensaje (de éxito o error de negocio)
                let template_bar = '';
                template_bar += `
                            <li style="list-style: none;">status: ${respuesta.status}</li>
                            <li style="list-style: none;">message: ${respuesta.message}</li>
                        `;
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);

                // --- Lógica de éxito ---
                // Solo si el backend dijo "success", reseteamos el form
                if (respuesta.status === 'success') {
                    // (v) Cargar lista actualizada
                    listarProductos();

                    // Limpiar formulario y recargar JSON base
                    $('#product-form').trigger('reset');
                    init();
                    
                    // Reseteamos el estado de edición
                    edit = false;
                    $('#productId').val('');
                    $('#product-form button[type="submit"]').text('Agregar Producto');
                }
                // Si status es 'error' (ej. duplicado), no hacemos nada y dejamos el form como está
            },
            
            // Callback si el servidor responde error (ej. HTTP 500 por error PHP)
            error: function (xhr, status, error) {
                let template_bar = `
                    <li style="list-style: none;">status: ${status} (Error Servidor)</li>
                    <li style="list-style: none;">message: ${error}</li>
                    <li style="list-style: none;">${xhr.responseText}</li>
                `;
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);
            }
        });
    });
// ... (tu app.js) ...

    /**
     * (NUEVO) Funcionalidad de Edición
     * Se activa al presionar el botón "Editar" en la tabla.
     * * CORREGIDO: Se usa $.ajax() en lugar de $.get() para capturar errores.
     */
    $(document).on('click', '.product-edit', function() {
        // 1. Obtenemos el ID del producto del atributo <tr>
        let element = $(this).closest('tr');
        let id = $(element).attr('productId');

        // 2. Solicitamos los datos de ESE producto al backend
        $.ajax({
            url: './backend/product-single.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json', // Esperamos un JSON de vuelta
            
            // 3. Callback de ÉXITO (si el servidor responde 200 OK)
            success: function(producto) { 
                // JQuery parsea el JSON automáticamente gracias a dataType
                
                // 4. Rellenamos el formulario
                $('#name').val(producto.nombre);
                $('#productId').val(producto.id); // Guardamos el ID en el input oculto
                
                let descripcionJSON = {
                    "precio": parseFloat(producto.precio), // Aseguramos que sea número
                    "unidades": parseInt(producto.unidades), // Aseguramos que sea entero
                    "modelo": producto.modelo,
                    "marca": producto.marca,
                    "detalles": producto.detalles,
                    "imagen": producto.imagen
                };
                $('#description').val(JSON.stringify(descripcionJSON, null, 2));

                // 5. Cambiamos el estado de la UI
                edit = true;
                $('#product-form button[type="submit"]').text('Actualizar Producto');

                // Opcional: Ocultar barra de estado si estaba visible
                $('#product-result').removeClass('d-block').addClass('d-none');
            },
            
            // 6. Callback de ERROR (si el servidor falla, 404, 500, etc.)
            error: function(xhr, status, error) {
                // Mostramos el error en la barra de estado
                let template_bar = `
                    <li style="list-style: none;">status: ${status} (Error al buscar producto)</li>
                    <li style="list-style: none;">message: ${error}</li>
                    <li style="list-style: none;">${xhr.responseText}</li>
                `;
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);
            }
        });
    });

    /**
     * (vi) Eliminar un producto.
     */
    $(document).on('click', '.product-delete', function() {
        if( confirm("De verdad deseas eliminar el Producto") ) {
            let element = $(this).closest('tr');
            let id = $(element).attr('productId');

            $.get('./backend/product-delete.php', { id: id }, function(response) {
                listarProductos(); // (vi) Cargar lista actualizada

                let respuesta = JSON.parse(response);
                let template_bar = '';
                template_bar += `
                            <li style="list-style: none;">status: ${respuesta.status}</li>
                            <li style="list-style: none;">message: ${respuesta.message}</li>
                        `;
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);
            });
        }
    });


    // --- FUNCIÓN HELPER ---

    /**
     * Función para cargar TODOS los productos (Req i)
     */
    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                let productos = JSON.parse(response);
                let template = '';

                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: '+producto.precio+'</li>';
                    descripcion += '<li>unidades: '+producto.unidades+'</li>';
                    descripcion += '<li>modelo: '+producto.modelo+'</li>';
                    descripcion += '<li>marca: '+producto.marca+'</li>';
                    descripcion += '<li>detalles: '+producto.detalles+'</li>';
                
                    // (MODIFICADO) Se añade el botón de Editar
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-edit btn btn-warning mr-2">
                                    Editar
                                </button>
                                <button class="product-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $('#products').html(template);
            }
        });
    }

});