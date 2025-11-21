<?php
namespace TECWEB\MYAPI;

abstract class DataBase {
    protected $conexion;
    protected $data; // Propiedad movida aquí

    public function __construct($db, $user = 'root', $pass = '@Nothing30') {
        $this->data = array(); // Inicializar $data
        $this->conexion = @mysqli_connect(
            'localhost',
            $user,
            $pass,
            $db
        );
    
        /**
         * NOTA: si la conexión falló $conexion contendrá false
         **/
        if(!$this->conexion) {
            die('¡Base de datos NO conextada!');
        }
    }

    // Método movido aquí
    public function getData() {
        // SE HACE LA CONVERSIÓN DE ARRAY A JSON
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>