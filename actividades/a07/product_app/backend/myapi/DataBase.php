<?php
    namespace myapi;

    //Clase abstracta DataBase
    //No puede ser instanciada directamente.
     
abstract class DataBase {
        
        /**
         * La conexión a la base de datos.
         * @var \mysqli $conexion 
         * Es protected para que las clases que heredan puedan usarla.
         */
        protected $conexion;

         //Constructor de la clase. inicializa la conexión a la base de datos, muestra un error si la conexión falla.
         
        public function __construct($db, $user, $pass) {
            
            // Se usa @ para suprimir el warning 
            // Se antepone \mysqli para usar la clase mysqli del espacio de nombres global
            $this->conexion = @new \mysqli('localhost', $user, $pass, $db);

            // Manejo de error en la conexión
            if ($this->conexion->connect_errno) {
                die('¡Base de datos NO conectada! Error: ' . $this->conexion->connect_error);
            } else {
                // Se establece el charset para la conexión (buena práctica)
                $this->conexion->set_charset("utf8");
            }
        }

        // Destructor
        public function __destruct() {
            if ($this->conexion) {
                $this->conexion->close();
            }
        }
    }
?>