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
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
}

// SE UTILIZA JQUERY PARA MANEJAR EVENTOS Y AJAX
$(document).ready(function () {
    
    // Carga el JSON base en el textarea al iniciar
    init();

    // i. Cargar toda la lista de productos NO eliminados al abrir la página.
    listarProductos();

    // ii. y iii. Cargar tabla y barra de estado con coincidencias al "ir" tecleando
    $('#search').on('keyup', function () {
        let search = $(this).val();
        
        // Si la búsqueda no está vacía, busca; si no, lista todo
        if (search) {
            $.ajax({
                url: './backend/product-search.php',
                type: 'GET',
                data: { search: search },
                success: function (response) {
                    let productos = JSON.parse(response);
                    let template = '';
                    let template_bar = '';

                    if(Object.keys(productos).length > 0) {
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

                            template_bar += `
                                <li>${producto.nombre}</il>
                            `;
                        });
                        // Mostrar barra de estado y poblar
                        $('#product-result').show();
                        $('#container').html(template_bar);
                        // Poblar tabla
                        $('#products').html(template);
                    } else {
                        // Si no hay resultados, limpiar y ocultar
                         $('#product-result').hide();
                         $('#products').html('');
                    }
                }
            });
        } else {
            // Si la búsqueda está vacía, oculta la barra y muestra todos
            $('#product-result').hide();
            listarProductos();
        }
    });

    // iv. y v. Registrar producto, recibir estatus y actualizar lista
    $('#product-form').on('submit', function (e) {
        e.preventDefault();

        // SE OBTIENE DESDE EL FORMULARIO EL JSON A ENVIAR
        var productoJsonString = $('#description').val();
        // SE CONVIERTE EL JSON DE STRING A OBJETO
        var finalJSON = JSON.parse(productoJsonString);
        // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
        finalJSON['nombre'] = $('#name').val();

        $.ajax({
            url: './backend/product-add.php',
            type: 'POST',
            data: JSON.stringify(finalJSON), // Se envía el JSON como string
            contentType: 'application/json;charset=UTF-8',
            success: function (response) {
                let respuesta = JSON.parse(response);
                
                // iv. Recibir estatus y mensaje
                let template_bar = '';
                template_bar += `
                            <li style="list-style: none;">status: ${respuesta.status}</li>
                            <li style="list-style: none;">message: ${respuesta.message}</li>
                        `;
                
                $('#product-result').show();
                $('#container').html(template_bar);

                // v. Cargar lista actualizada
                listarProductos();

                // Limpiar formulario y recargar JSON base
                $('#product-form').trigger('reset');
                init();
            }
        });
    });

    // vi. Eliminar producto y actualizar lista
    // Se usa delegación de eventos en 'document' porque los botones se crean dinámicamente
    $(document).on('click', '.product-delete', function () {
        if( confirm("De verdad deseas eliminar el Producto") ) {
            // Se busca el <tr> padre y se obtiene su atributo 'productId'
            let element = $(this).closest('tr');
            let id = $(element).attr('productId');
            
            $.ajax({
                url: './backend/product-delete.php',
                type: 'GET',
                data: { id: id },
                success: function (response) {
                    let respuesta = JSON.parse(response);
                    
                    // Mostrar mensaje de estatus
                    let template_bar = '';
                    template_bar += `
                                <li style="list-style: none;">status: ${respuesta.status}</li>
                                <li style="list-style: none;">message: ${respuesta.message}</li>
                            `;
                    $('#product-result').show();
                    $('#container').html(template_bar);

                    // vi. Cargar lista actualizada
                    listarProductos();
                }
            });
        }
    });

    // FUNCIÓN PARA LISTAR PRODUCTOS (REUTILIZABLE)
    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function (response) {
                let productos = JSON.parse(response);
                let template = '';

                if(Object.keys(productos).length > 0) {
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
                    $('#products').html(template);
                } else {
                     $('#products').html(''); // Limpia la tabla si no hay productos
                }
            }
        });
    }

});