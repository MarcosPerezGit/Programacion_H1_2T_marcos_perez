<?php
require_once '../controlador/UsuariosController.php';

// Creamos una instancia del controller para gestionar usuarios
$controller = new UsuariosController();

// Verificamos si se ha enviado un ID de usuario mediante GET
if (isset($_GET['id_usuario'])) {

    // Obtenemos el ID del usuario desde la URL
    $id_usuario = $_GET['id_usuario'];

    // Intentamos eliminar al usuario utilizando el controlador
    $deleted = $controller->eliminarUsuario($id_usuario);
    
    // Verificamos si la eliminacion ha ido bien
    if ($deleted) {
        header("Location: lista_usuario.php?message=Usuario eliminado con exito.");
    } else {
        header("Location: lista_usuario.php?error=Error al eliminar usuario.");
    }
} else {
    header("Location: lista_usuario.php?error=ID del usuario no especificado.");
}
?>
