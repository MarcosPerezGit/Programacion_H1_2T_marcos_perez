<?php
class Conexion {
    // Datos de conexion a la base de datos
    private $servidor = 'localhost';
    private $usuario = 'root';
    private $password = '1234';
    private $base_datos = 'streamWeb';

    public $conexion;

    public function __construct() {
        // Creamos la conexion con MySQL
        $this->conexion = new mysqli($this->servidor, $this->usuario, $this->password, $this->base_datos);

        // Verificamos si hay error en la conexion
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        // Establecemosel conjunto de caracteres a UTF8
        $this->conexion->set_charset("utf8");
    }
// Con esta funcion cerramos la conexion
    public function cerrar() {
        $this->conexion->close();
    }
}
?>