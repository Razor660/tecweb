// PASO 5: Se elimina la variable baseJSON

$(document).ready(function(){
    let edit = false;

    // PASO 5: Se elimina la inicialización del <textarea>
    $('#product-result').hide();
    listarProductos();

    // Función para mostrar mensajes de estado (PASO 6)
    function showStatus(message, isError = false) {
        $('#product-result').show();
        let statusClass = isError ? 'text-danger' : 'text-success';
        let template_bar = `<li style="list-style: none;" class="${statusClass}">${message}</li>`;
        $('#container').html(template_bar);
    }

    // Función para limpiar el formulario y el estado
    function resetForm() {
        $('#product-form').trigger('reset');
        $('#productId').val(''); // Asegurarse de limpiar el ID oculto
        edit = false;
        $('button.btn-primary').text("Agregar Producto");
        // Opcional: Ocultar la barra de estado después de un tiempo
        // setTimeout(() => { $('#product-result').hide(); }, 3000);
    }

    // VALIDACIONES ON-BLUR (PASO 5.1 y 6)

    // PASO 7: Validación asíncrona de nombre
    $('#name').blur(function() {
        let name = $(this).val();
        let id = $('#productId').val(); // Obtener el ID para edición
        if (name.trim() === '') {
            showStatus('El campo "Nombre" no puede estar vacío.', true);
        } else {
            // Conexión asíncrona
            $.post('./backend/product-check-name.php', { nombre: name, id: id }, (response) => {
                let result = JSON.parse(response);
                if (result.exists) {
                    showStatus(result.message, true); // Es un error si ya existe
                } else {
                    showStatus(result.message, false); // Mensaje de "Nombre disponible"
                }
            });
        }
    });

    // Validación simple para otros campos (puedes hacerlas más complejas)
    $('#marca').blur(function() {
        if ($(this).val().trim() === '') showStatus('El campo "Marca" no puede estar vacío.', true);
    });
    $('#modelo').blur(function() {
        if ($(this).val().trim() === '') showStatus('El campo "Modelo" no puede estar vacío.', true);
    });
    $('#precio').blur(function() {
        let precio = parseFloat($(this).val());
        if (isNaN(precio) || precio <= 0) showStatus('El campo "Precio" debe ser un número positivo.', true);
    });
    $('#unidades').blur(function() {
        let unidades = parseInt($(this).val());
        if (isNaN(unidades) || unidades < 0) showStatus('El campo "Unidades" debe ser un número entero no negativo.', true);
    });
    $('#detalles').blur(function() {
        if ($(this).val().trim() === '') showStatus('El campo "Detalles" no puede estar vacío.', true);
    });
    $('#imagen').blur(function() {
        if ($(this).val().trim() === '') showStatus('El campo "Imagen" no puede estar vacío.', true);
    });

    
    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                const productos = JSON.parse(response);
                if(Object.keys(productos).length > 0) {
                    let template = '';
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
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
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
                }
            }
        });
    }

    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/product-search.php?search='+$('#search').val(),
                data: {search},
                type: 'GET',
                success: function (response) {
                    if(!response.error) {
                        const productos = JSON.parse(response);
                        if(Object.keys(productos).length > 0) {
                            let template = '';
                            let template_bar = '';
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
                                        <td><a href="#" class="product-item">${producto.nombre}</a></td>
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
                            $('#product-result').show();
                            $('#container').html(template_bar);
                            $('#products').html(template);    
                        }
                    }
                }
            });
        }
        else {
            $('#product-result').hide();
        }
    });

    $('#product-form').submit(e => {
        e.preventDefault();

        // PASO 5.2: Validación antes de enviar
        const requiredFields = ['#name', '#marca', '#modelo', '#precio', '#unidades', '#detalles', '#imagen'];
        let isValid = true;
        
        for (const field of requiredFields) {
            if ($(field).val().trim() === '') {
                isValid = false;
                let fieldName = $(field).attr('placeholder'); // Asume que el placeholder es un buen nombre
                showStatus(`El campo "${fieldName}" es obligatorio.`, true);
                $(field).focus(); // Poner foco en el campo vacío
                break; // Detener en el primer error
            }
        }

        if (!isValid) {
            return; // Detiene el envío del formulario si no es válido
        }

        // PASO 5: Construir postData desde los campos del formulario
        const postData = {
            nombre: $('#name').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            precio: $('#precio').val(),
            detalles: $('#detalles').val(),
            unidades: $('#unidades').val(),
            imagen: $('#imagen').val(),
            id: $('#productId').val()
        };

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
        
        $.post(url, postData, (response) => {
            let respuesta = JSON.parse(response);
            
            // Mostrar mensaje del backend
            showStatus(respuesta.message, (respuesta.status === 'error'));

            // Solo si fue exitoso, reiniciar formulario
            if (respuesta.status === 'success') {
                resetForm(); // Limpia formulario, resetea 'edit' y texto de botón
                listarProductos();
            }
            // Si hay error (ej. nombre duplicado), el formulario NO se limpia
            // para que el usuario pueda corregirlo.
        });
    });

    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('productId');
            $.post('./backend/product-delete.php', {id}, (response) => {
                $('#product-result').hide(); // Oculta barra al eliminar
                listarProductos();
            });
        }
    });

    $(document).on('click', '.product-item', (e) => {
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('productId');
        $.post('./backend/product-single.php', {id}, (response) => {
            let product = JSON.parse(response);
            
            // PASO 5: Rellenar los campos del formulario
            $('#name').val(product.nombre);
            $('#marca').val(product.marca);
            $('#modelo').val(product.modelo);
            $('#precio').val(product.precio);
            $('#detalles').val(product.detalles);
            $('#unidades').val(product.unidades);
            $('#imagen').val(product.imagen);
            $('#productId').val(product.id); // ID oculto

            // PASO 5: Se elimina la lógica del <textarea>
            
            edit = true;
            // PASO 2: Cambiar texto del botón
            $('button.btn-primary').text("Modificar Producto");

            // Ocultar barra de estado al seleccionar un item
            $('#product-result').hide(); 
        });
        e.preventDefault();
    });    
});