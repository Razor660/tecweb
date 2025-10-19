// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

// FUNCIÓN CALLBACK DE BOTÓN "Buscar"
function buscarID(e) {
    /**
     * Revisar la siguiente información para entender porqué usar event.preventDefault();
     * http://qbit.com.mx/blog/2013/01/07/la-diferencia-entre-return-false-preventdefault-y-stoppropagation-en-jquery/#:~:text=PreventDefault()%20se%20utiliza%20para,escuche%20a%20trav%C3%A9s%20del%20DOM
     * https://www.geeksforgeeks.org/when-to-use-preventdefault-vs-return-false-in-javascript/
     */
    e.preventDefault();

    // SE OBTIENE EL ID A BUSCAR
    var id = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);    // similar a eval('('+client.responseText+')');
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                let descripcion = '';
                    descripcion += '<li>precio: '+productos.precio+'</li>';
                    descripcion += '<li>unidades: '+productos.unidades+'</li>';
                    descripcion += '<li>modelo: '+productos.modelo+'</li>';
                    descripcion += '<li>marca: '+productos.marca+'</li>';
                    descripcion += '<li>detalles: '+productos.detalles+'</li>';
                
                // SE CREA UNA PLANTILLA PARA CREAR LA(S) FILA(S) A INSERTAR EN EL DOCUMENTO HTML
                let template = '';
                    template += `
                        <tr>
                            <td>${productos.id}</td>
                            <td>${productos.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id="+id);
}

// NUEVA FUNCIÓN CALLBACK DE BOTÓN "Buscar"
function buscarProducto(e) {
    /**
     * Prevenimos el comportamiento default del formulario
     */
    e.preventDefault();

    // SE OBTIENE EL TÉRMINO A BUSCAR
    var searchTerm = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL ARRAY DE PRODUCTOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);
            
            // SE VERIFICA SI EL ARRAY JSON TIENE DATOS
            if(productos.length > 0) {
                
                // SE INICIALIZA LA PLANTILLA
                let template = '';
                
                // SE RECORRE EL ARRAY DE PRODUCTOS
                productos.forEach(producto => {
                    // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DE CADA PRODUCTO
                    let descripcion = '';
                        descripcion += '<li>precio: '+producto.precio+'</li>';
                        descripcion += '<li>unidades: '+producto.unidades+'</li>';
                        descripcion += '<li>modelo: '+producto.modelo+'</li>';
                        descripcion += '<li>marca: '+producto.marca+'</li>';
                        descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                    // SE AGREGA LA FILA A LA PLANTILLA
                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;
                }); // Fin de forEach

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            } else {
                // Si no hay resultados, limpiar la tabla y mostrar mensaje
                document.getElementById("productos").innerHTML = '<tr><td colspan="3">No se encontraron productos.</td></tr>';
            }
        }
    };
    // SE ENVÍA EL TÉRMINO DE BÚSQUEDA. 
    // El backend (read.php) lo espera como 'id'
    client.send("id=" + searchTerm);
}

// FUNCIÓN CALLBACK DE BOTÓN "Agregar Producto"
function agregarProducto(e) {
    e.preventDefault();

    // SE OBTIENE DESDE EL FORMULARIO EL JSON A ENVIAR
    var productoJsonString = document.getElementById('description').value;
    
    // SE CONVIERTE EL JSON DE STRING A OBJETO
    var finalJSON;
    try {
        finalJSON = JSON.parse(productoJsonString);
    } catch (error) {
        alert("Error: El formato del JSON en 'descripción' no es válido.");
        return;
    }

    // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
    finalJSON['nombre'] = document.getElementById('name').value;

    // --- INICIO DE VALIDACIONES (a-g) ---

    // a. Nombre: requerido y <= 100 caracteres
    if (!finalJSON.nombre || finalJSON.nombre.trim() === "") {
        alert("Error (a): El nombre es requerido.");
        return;
    }
    if (finalJSON.nombre.length > 100) {
        alert("Error (a): El nombre no debe exceder los 100 caracteres.");
        return;
    }

    // b. Marca: requerida y de una lista
    // (Como no hay lista en HTML, definimos una aquí para validar)
    var marcasValidas = ['HP', 'Lenovo', 'Dell', 'Apple', 'Samsung', 'LG', 'Xiaomi', 'Motorola', 'ASUS', 'Acer', 'NA'];
    if (!finalJSON.marca || !marcasValidas.includes(finalJSON.marca)) {
        alert("Error (b): La marca es requerida y debe ser una marca válida (Ej: " + marcasValidas.join(', ') + ")");
        return;
    }

    // c. Modelo: requerido, texto alfanumérico y <= 25 caracteres
    if (!finalJSON.modelo || finalJSON.modelo.trim() === "") {
        alert("Error (c): El modelo es requerido.");
        return;
    }
    if (finalJSON.modelo.length > 25) {
        alert("Error (c): El modelo no debe exceder los 25 caracteres.");
        return;
    }
    // Opcional: Validación estricta de alfanumérico (letras, números, guiones)
    // var modeloRegex = /^[a-zA-Z0-9-]+$/; 
    // if (!modeloRegex.test(finalJSON.modelo)) {
    //     alert("Error (c): El modelo debe ser alfanumérico (solo letras, números y guiones).");
    //     return;
    // }


    // d. Precio: requerido y > 99.99
    if (finalJSON.precio === undefined || finalJSON.precio === null) {
         alert("Error (d): El precio es requerido.");
         return;
    }
    var precioNum = parseFloat(finalJSON.precio);
    if (isNaN(precioNum) || precioNum <= 99.99) {
        alert("Error (d): El precio debe ser un número mayor a 99.99");
        return;
    }

    // e. Detalles: opcional, <= 250 caracteres
    if (finalJSON.detalles && finalJSON.detalles.length > 250) {
        alert("Error (e): Los detalles no deben exceder los 250 caracteres.");
        return;
    }

    // f. Unidades: requeridas y >= 0
    if (finalJSON.unidades === undefined || finalJSON.unidades === null) {
        alert("Error (f): Las unidades son requeridas.");
        return;
    }
    var unidadesNum = parseInt(finalJSON.unidades);
    if (isNaN(unidadesNum) || !Number.isInteger(unidadesNum) || unidadesNum < 0) {
        alert("Error (f): Las unidades deben ser un número entero mayor o igual a 0.");
        return;
    }

    // g. Imagen: opcional, si no, poner default
    if (!finalJSON.imagen || finalJSON.imagen.trim() === "") {
        finalJSON.imagen = "img/default.png";
    }
    
    // --- FIN DE VALIDACIONES ---


    // SE OBTIENE EL STRING DEL JSON FINAL VALIDADO
    productoJsonString = JSON.stringify(finalJSON,null,2);

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/create.php', true);
    client.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            
            // Mostrar la respuesta del servidor (éxito o error)
            window.alert(client.responseText); 
            
            // Opcional: Limpiar formulario si fue exitoso
            if(client.responseText.includes("Éxito")) {
                document.getElementById('name').value = '';
                init(); // Reinicia el JSON de descripción
            }
        }
    };
    client.send(productoJsonString);
}

// SE CREA EL OBJETO DE CONEXIÓN COMPATIBLE CON EL NAVEGADOR
function getXMLHttpRequest() {
    var objetoAjax;

    try{
        objetoAjax = new XMLHttpRequest();
    }catch(err1){
        /**
         * NOTA: Las siguientes formas de crear el objeto ya son obsoletas
         *       pero se comparten por motivos historico-académicos.
         */
        try{
            // IE7 y IE8
            objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(err2){
            try{
                // IE5 y IE6
                objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(err3){
                objetoAjax = false;
            }
        }
    }
    return objetoAjax;
}

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
}