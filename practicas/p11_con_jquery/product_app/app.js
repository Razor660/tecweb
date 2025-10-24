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
    /**
     * Convierte el JSON a string para poder mostrarlo
     * Se usa jQuery para seleccionar el elemento y .val() para asignar el valor
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
}

// Se encapsula todo el código en $(document).ready() para asegurar que el DOM esté cargado
$(document).ready(function() {
    
    // Carga el JSON base en el textarea al iniciar
    init();

    // (i) Cargar toda la lista de productos NO eliminados al abrir la página.
    listarProductos();

    // --- MANEJADORES DE EVENTOS JQUERY ---

    /**
     * (ii) y (iii) Cargar tabla y barra de estado al "teclear" en búsqueda.
     * Se usa el evento 'keyup' para detectar cada tecla presionada.
     */
    $('#search').keyup(function() {
        let search = $(this).val();

        // Si el campo de búsqueda no está vacío, realiza la búsqueda
        if(search) {
            $.ajax({
                url: './backend/product-search.php',
                type: 'GET',
                data: { search: search }, // jQuery formatea la URL (product-search.php?search=valor)
                success: function(response) {
                    let productos = JSON.parse(response);
                    let template = '';
                    let template_bar = '';

                    if(Object.keys(productos).length > 0) {
                        productos.forEach(producto => {
                            // (iii) Cargar nombres en la barra de estado
                            template_bar += `<li>${producto.nombre}</li>`;

                            // (ii) Cargar productos en la tabla
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
                                        <button class="product-delete btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        // Mostrar barra de estado y actualizar contenedor
                        $('#product-result').removeClass('d-none').addClass('d-block');
                        $('#container').html(template_bar);
                    } else {
                        // Ocultar barra si no hay resultados
                         $('#product-result').removeClass('d-block').addClass('d-none');
                         template = `<tr><td colspan="4">No se encontraron productos.</td></tr>`;
                    }
                    // Actualizar tabla
                    $('#products').html(template);
                }
            });
        } else {
            // Si la búsqueda está vacía, oculta la barra y muestra todos los productos
            $('#product-result').removeClass('d-block').addClass('d-none');
            listarProductos();
        }
    });

    /**
     * (iv) y (v) Registrar un producto.
     * Se usa el evento 'submit' del formulario.
     */
    $('#product-form').submit(function(e) {
        // Previene el comportamiento por defecto del formulario (recargar la página)
        e.preventDefault();

        // Se obtienen los datos del formulario
        var productoJsonString = $('#description').val();
        var finalJSON = JSON.parse(productoJsonString);
        finalJSON['nombre'] = $('#name').val();
        productoJsonString = JSON.stringify(finalJSON, null, 2);

        // Se envía el producto usando $.ajax (POST)
        $.ajax({
            url: './backend/product-add.php',
            type: 'POST',
            data: productoJsonString, // Se envía el JSON como string
            contentType: 'application/json;charset=UTF-8',
            success: function(response) {
                // (iv) Recibir estatus y mensaje
                let respuesta = JSON.parse(response);
                let template_bar = '';
                template_bar += `
                            <li style="list-style: none;">status: ${respuesta.status}</li>
                            <li style="list-style: none;">message: ${respuesta.message}</li>
                        `;
                // Mostrar barra de estado
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);

                // (v) Cargar lista actualizada
                listarProductos();

                // Limpiar formulario y recargar JSON base
                $('#product-form').trigger('reset'); // Limpia el input "name"
                init(); // Recarga el textarea
            }
        });
    });

    /**
     * (vi) Eliminar un producto.
     * Se usa delegación de eventos (.on 'click') porque los botones
     * de eliminar se crean dinámicamente.
     */
    $(document).on('click', '.product-delete', function() {
        if( confirm("De verdad deseas eliminar el Producto") ) {
            // $(this) se refiere al botón presionado
            // .closest('tr') busca el ancestro <tr> más cercano
            let element = $(this).closest('tr');
            let id = $(element).attr('productId'); // Obtiene el ID del atributo

            // Se envía la petición de borrado por GET
            $.get('./backend/product-delete.php', { id: id }, function(response) {
                // (vi) Cargar lista actualizada
                listarProductos();

                // Mostrar respuesta del servidor
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
     * Se usa $.ajax (GET)
     */
    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                // Parsea la respuesta JSON
                let productos = JSON.parse(response);
                let template = '';

                // Recorre los productos y crea la plantilla HTML
                productos.forEach(producto => {
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
                                <button class="product-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                // Inserta la plantilla en el <tbody>
                $('#products').html(template);
            }
        });
    }

});