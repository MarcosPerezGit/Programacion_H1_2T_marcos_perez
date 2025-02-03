<?php
require_once '../config/class_conexion.php';

class Usuario {
    public $conexion; 

    // Instanciamos conexion a la base de datos
    public function __construct() {
        $this->conexion = new Conexion();
    }

    // Funcion que sirve para verificar si el correo ya esta registrado
    public function correoExistente($correo) {
        $query = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0; 
    }

    // Funcion que sirve para verificar si el correo y contraseña coinciden
    public function agregarUsuario($nombre, $apellidos, $correo, $edad, $contraseña, $id_plan, $paquetes, $duracion_suscripcion) {
        // Verificamos si el correo ya existe
        if ($this->correoExistente($correo)) {
            return "El correo ya está registrado.";
        }
    
        // Hasheamos la contraseña con password_hash
        $hashed = password_hash($contraseña, PASSWORD_DEFAULT);
    
        // Insertamos el usuario en la tabla usuarios
        $query = "INSERT INTO usuarios (nombre, apellidos, correo, edad, contraseña, id_plan, duracion_suscripcion) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("sssisis", $nombre, $apellidos, $correo, $edad, $hashed, $id_plan, $duracion_suscripcion);
    
        if ($stmt->execute()) {
            // Cogemos el ID del usuario recien insertado
            $id_usuario = $stmt->insert_id;
    
            // Insertamos los paquetes adicionales en la tabla usuarios_paquetes
            if (!empty($paquetes)) {
                foreach ($paquetes as $id_paquete) {
                    $query_paquete = "INSERT INTO usuarios_paquetes (id_usuario, id_paquete) VALUES (?, ?)";
                    $stmt_paquete = $this->conexion->conexion->prepare($query_paquete);
                    $stmt_paquete->bind_param("ii", $id_usuario, $id_paquete);
                    $stmt_paquete->execute();
                    $stmt_paquete->close();
                }
            }
    
            $stmt->close();
            return true;
        } else {
            error_log("Error al agregar usuario: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    // Funcion que sirve para obtener lo que necesitamos del Usuario
    public function obtenerUsuario() {
        // Consulta con JOIN para obtener el nombre del plan base, los paquetes adicionales y el costo total
        $query = "SELECT u.*, p.nombre_plan, p.precio AS precio_plan, 
                        GROUP_CONCAT(pa.nombre_paquete SEPARATOR ', ') AS paquetes_adicionales,
                        SUM(pa.precio) AS precio_paquetes,
                        (p.precio + IFNULL(SUM(pa.precio), 0)) AS costo_total
                FROM usuarios u
                JOIN planes_base p ON u.id_plan = p.id_plan
                LEFT JOIN usuarios_paquetes up ON u.id_usuario = up.id_usuario
                LEFT JOIN paquetes pa ON up.id_paquete = pa.id_paquete
                GROUP BY u.id_usuario";
        $resultado = $this->conexion->conexion->query($query);
    
        $usuarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }

    // Funcion que sirve para obtener el usuario por ID
    public function obtenerUsuarioPorId($id_usuario) {
        $query = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $sentencia = $this->conexion->conexion->prepare($query);
        $sentencia->bind_param("i", $id_usuario);
        $sentencia->execute();
        $resultado = $sentencia->get_result();
        return $resultado->fetch_assoc(); //// Devuelve un array con los datos del usuario
    }

    // Funcion que sirve para eliminar un Usuario de la base de datos.
    public function eliminarUsuario($id_usuario) {
        $query = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            echo "Socio eliminado con éxito.";
        } else {
            echo "Error al eliminar socio: " . $stmt->error;
        }

        $stmt->close();
    }
    
    // Funcion que sirve para obtener los paquetes adicionales contratados por el usuario
    public function obtenerPaquetesPorUsuario($id_usuario) {
        $query = "SELECT id_paquete FROM usuarios_paquetes WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        $paquetes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $paquetes[] = $fila;
        }
        return $paquetes;
    }
}
