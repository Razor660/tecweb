<?php
    namespace myapi;

    require_once __DIR__.'/DataBase.php';

    //Clase Products extiende DataBase y maneja el CRUD 
    class Products extends DataBase {
        
        /**
         * @var array $data Almacena la respuesta que será devuelta como JSON.
         */
        private $data;

        
         // Constructor Inicializa el array de datos y llama al constructor de la clase padre para establecer la conexión
         
        public function __construct($db = 'marketzone', $user = 'root', $pass = '@Nothing30') {
            // Se inicializa el atributo $data como un array vacío
            $this->data = array();
            
            // Se llama al constructor de la SuperClase (DataBase)
            // Esto inicializa $this->conexion
            parent::__construct($db, $user, $pass);
        }

        //Obtiene todos los productos no eliminados. Almacena el resultado en $this->data.
        public function list() {
            $sql = "SELECT * FROM productos WHERE eliminado = 0";
            if ($result = $this->conexion->query($sql)) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                if (!is_null($rows)) {
                    // Codificamos a UTF-8
                    foreach ($rows as $num => $row) {
                        foreach ($row as $key => $value) {
                            $this->data[$num][$key] = utf8_encode($value);
                        }
                    }
                }
                $result->free();
            } else {
                $this->data['error'] = 'Query Error: ' . $this->conexion->error;
            }
        }

        //Busca productos por ID, nombre, marca o detalles. Almacena el resultado en $this->data.
         
        public function search($search) {
            $search = $this->conexion->real_escape_string($search); // Seguridad
            $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
            
            if ($result = $this->conexion->query($sql)) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                if (!is_null($rows)) {
                    foreach ($rows as $num => $row) {
                        foreach ($row as $key => $value) {
                            $this->data[$num][$key] = utf8_encode($value);
                        }
                    }
                }
                $result->free();
            } else {
                $this->data['error'] = 'Query Error: ' . $this->conexion->error;
            }
        }

        //Obtiene un solo producto por su ID.
        public function single($id) {
            $id = $this->conexion->real_escape_string($id); // Seguridad
            $sql = "SELECT * FROM productos WHERE id = {$id}";
            
            if ($result = $this->conexion->query($sql)) {
                $row = $result->fetch_assoc();
                if (!is_null($row)) {
                    foreach ($row as $key => $value) {
                        $this->data[$key] = utf8_encode($value);
                    }
                }
                $result->free();
            } else {
                $this->data['error'] = 'Query Error: ' . $this->conexion->error;
            }
        }

        //Obtiene un solo producto por su Nombre.
        public function singleByName($name) {
            $name = $this->conexion->real_escape_string($name); // Seguridad
            $sql = "SELECT * FROM productos WHERE nombre = '{$name}' AND eliminado = 0";

            if ($result = $this->conexion->query($sql)) {
                $row = $result->fetch_assoc();
                if (!is_null($row)) {
                    foreach ($row as $key => $value) {
                        $this->data[$key] = utf8_encode($value);
                    }
                }
                // Si no hay filas, $this->data permanecerá vacío (o lo que se inicializó)
                $result->free();
            } else {
                $this->data['error'] = 'Query Error: ' . $this->conexion->error;
            }
        }

        //Agrega un nuevo producto. Espera un objeto o array con los datos.
         
        
        public function add($obj) {
            // Asumimos que $obj es un objeto (como stdClass)
            $nombre = $this->conexion->real_escape_string($obj->nombre);
            
            // Primero validamos si ya existe
            $sql_check = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
            $result_check = $this->conexion->query($sql_check);

            if ($result_check->num_rows == 0) {
                // Preparamos los demás datos
                $marca = $this->conexion->real_escape_string($obj->marca);
                $modelo = $this->conexion->real_escape_string($obj->modelo);
                $precio = floatval($obj->precio); // Asegurar tipo
                $detalles = $this->conexion->real_escape_string($obj->detalles);
                $unidades = intval($obj->unidades); // Asegurar tipo
                $imagen = $this->conexion->real_escape_string($obj->imagen);

                $sql = "INSERT INTO productos VALUES (null, '{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";
                
                if ($this->conexion->query($sql)) {
                    $this->data['status'] = "success";
                    $this->data['message'] = "Producto agregado";
                } else {
                    $this->data['status'] = "error";
                    $this->data['message'] = "ERROR: No se ejecuto $sql. " . $this->conexion->error;
                }
            } else {
                $this->data['status'] = "error";
                $this->data['message'] = "Ya existe un producto con ese nombre";
            }
            
            $result_check->free();
        }

        //Edita un producto existente.
        public function edit($obj) {
            $id = $this->conexion->real_escape_string($obj->id);
            $nombre = $this->conexion->real_escape_string($obj->nombre);
            $marca = $this->conexion->real_escape_string($obj->marca);
            $modelo = $this->conexion->real_escape_string($obj->modelo);
            $precio = floatval($obj->precio);
            $detalles = $this->conexion->real_escape_string($obj->detalles);
            $unidades = intval($obj->unidades);
            $imagen = $this->conexion->real_escape_string($obj->imagen);

            $sql =  "UPDATE productos SET nombre='{$nombre}', marca='{$marca}', ";
            $sql .= "modelo='{$modelo}', precio={$precio}, detalles='{$detalles}', "; 
            $sql .= "unidades={$unidades}, imagen='{$imagen}' WHERE id={$id}";

            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto actualizado";
            } else {
                $this->data['status'] = "error";
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . $this->conexion->error;
            }
        }

        //Realiza un borrado lógico de un producto (SET eliminado=1).
        public function delete($id) {
            $id = $this->conexion->real_escape_string($id); // Seguridad
            $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
            
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto eliminado";
            } else {
                $this->data['status'] = "error";
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . $this->conexion->error;
            }
        }

        //Devuelve los datos (propiedad $data) como un string JSON.
        public function getData() {
            return json_encode($this->data, JSON_PRETTY_PRINT);
        }
        // ... (justo después del método getData()) ...

        /**
         * Verifica si un nombre de producto ya existe, 
         * opcionalmente excluyendo un ID (para edición).
         * Devuelve un array con el estado, no lo guarda en $this->data.
         */
        public function checkName($name, $id = null) {
            $name = $this->conexion->real_escape_string($name);
            
            $idClause = "";
            if (!empty($id)) {
                $id = $this->conexion->real_escape_string($id);
                $idClause = " AND id != {$id}";
            }

            $sql = "SELECT * FROM productos WHERE nombre = '{$name}' AND eliminado = 0{$idClause}";
            $result = $this->conexion->query($sql);

            $data = array();
            if ($result && $result->num_rows > 0) {
                $data['exists'] = true;
                $data['message'] = 'Ese nombre de producto ya existe';
            } else {
                $data['exists'] = false;
                $data['message'] = 'Nombre disponible';
            }
            
            if ($result) {
                $result->free();
            }
            
            return $data; // Devuelve el array directamente
        }
    } 
?>