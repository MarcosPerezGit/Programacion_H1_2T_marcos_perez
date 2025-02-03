<?php

require_once '../modelo/class_usuario.php';

class UsuariosController {
    private $usuario;

    // Instanciamos la clase Usuario para poder usar sus metodos
    public function __construct() {
        $this->usuario = new Usuario();
    }

    // Metodo para registrar un usuario
    public function agregarUsuario($nombre, $apellidos, $correo, $edad, $contraseña, $id_plan, $paquetes, $duracion_suscripcion) {
        return $this->usuario->agregarUsuario($nombre, $apellidos, $correo, $edad, $contraseña, $id_plan, $paquetes, $duracion_suscripcion);
    }

    // Metodo para obtener todos los usuarios
    public function listarUsuario() {
        return $this->usuario->obtenerUsuario();
    }
    // Metodo para obtener un usuario por su id
    public function obtenerUsuarioporId($id_usuario) {
        return $this->usuario->obtenerUsuarioPorId($id_usuario);
    }

    // Metodo para actualizar un usuario
    public function obtenerPaquetesPorUsuario($id_usuario) {
        return $this->usuario->obtenerPaquetesPorUsuario($id_usuario);
    }

    // Metodo para actualizar un usuario
    public function actualizarUsuario($id_usuario, $nombre, $apellidos, $correo, $edad, $id_plan, $paquetes, $duracion_suscripcion) {
        // Actualizamos los datos del usuario en la base de datos
        $query = "UPDATE usuarios SET nombre = ?, apellidos = ?, correo = ?, edad = ?, id_plan = ?, duracion_suscripcion = ? WHERE id_usuario = ?";
        $stmt = $this->usuario->conexion->conexion->prepare($query);
        $stmt->bind_param("ssssisi", $nombre, $apellidos, $correo, $edad, $id_plan, $duracion_suscripcion, $id_usuario);
    
        if (!$stmt->execute()) {
            return "Error al actualizar usuario: " . $stmt->error;
        }
    
        // Eliminamos los paquetes actuales del usuario para luego poder añadir los nuevos
        $query_eliminar_paquetes = "DELETE FROM usuarios_paquetes WHERE id_usuario = ?";
        $stmt_eliminar_paquetes = $this->usuario->conexion->conexion->prepare($query_eliminar_paquetes);
        $stmt_eliminar_paquetes->bind_param("i", $id_usuario);
        $stmt_eliminar_paquetes->execute();
    
        // Insertamos los nuevos paquetes adicionales si existen
        if (!empty($paquetes)) {
            foreach ($paquetes as $id_paquete) {
                $query_insertar_paquete = "INSERT INTO usuarios_paquetes (id_usuario, id_paquete) VALUES (?, ?)";
                $stmt_insertar_paquete = $this->usuario->conexion->conexion->prepare($query_insertar_paquete);
                $stmt_insertar_paquete->bind_param("ii", $id_usuario, $id_paquete);
                $stmt_insertar_paquete->execute();
            }
        }
    
        return "Usuario actualizado con éxito.";
    }

    // Metodo para eliminar un usuario
    public function eliminarUsuario($id_usuario) {
        return $this->usuario->eliminarUsuario($id_usuario);
    }
}
?>
