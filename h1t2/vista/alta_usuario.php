<?php
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();
$error_message = ''; // Variable para almacenar mensajes de error
$success_message = ''; // Variable para almacenar mensajes de exito

// Obtenemos los valores del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $edad = $_POST['edad'];
    $contraseña = $_POST['contraseña'];
    $id_plan = $_POST['id_plan'];
    $paquetes = $_POST['paquetes'] ?? [];  // Utilizamos null para el caso de que no se seleccionen paquetes.
    $duracion_suscripcion = $_POST['duracion_suscripcion'];

    // Validaciones antes de agregar el usuario
    if ($edad < 18 && (!in_array(3, $paquetes) || count($paquetes) > 1)) {
        $error_message = "Los usuarios menores de 18 años solo pueden contratar el Pack Infantil.";
    } elseif ($id_plan == 1 && count($paquetes) > 1) {
        $error_message = "Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional.";
    } elseif (in_array(1, $paquetes) && $duracion_suscripcion != 'Anual') {
        $error_message = "El Pack Deporte solo puede ser contratado con una duración anual.";
    } else {
        $usuarios = $controller->agregarUsuario($nombre_usuario, $apellidos, $correo, $edad, $contraseña, $id_plan, $paquetes, $duracion_suscripcion);
        if (!$usuarios) {
            $error_message = "Error al agregar Usuario. Por favor, verifica los datos.";
        } else {
            $success_message = "Usuario agregado con éxito.";
            header("location: ../vista/lista_usuario.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Agregar Nuevo Usuario</h2>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form action="alta_usuario.php" method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="nombre_usuario" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Ingrese el nombre del usuario" required>
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Ingrese los Apellidos del usuario" required>
            </div>
            <div class="col-md-6">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese el correo del usuario" required>
            </div>
            <div class="col-md-6">
                <label for="edad" class="form-label">Edad</label>
                <input type="text" class="form-control" id="edad" name="edad" placeholder="Ingrese la edad del usuario" required>
            </div>
            <div class="col-md-6">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Ingrese la contraseña del usuario" required>
            </div>
            <div class="col-md-6">
                <label for="id_plan" class="form-label">Plan Base</label>
                <select class="form-control" id="id_plan" name="id_plan" required>
                    <option value="1">Básico</option>
                    <option value="2">Estándar</option>
                    <option value="3">Premium</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Paquetes Adicionales</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paquetes[]" value="1" id="deporte">
                    <label class="form-check-label" for="deporte">Deporte</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paquetes[]" value="2" id="cine">
                    <label class="form-check-label" for="cine">Cine</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paquetes[]" value="3" id="infantil">
                    <label class="form-check-label" for="infantil">Infantil</label>
                </div>
            </div>
            <div class="col-md-6">
                <label for="duracion_suscripcion" class="form-label">Duración de la Suscripción</label>
                <select class="form-control" id="duracion_suscripcion" name="duracion_suscripcion" required>
                    <option value="Mensual">Mensual</option>
                    <option value="Anual">Anual</option>
                </select>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Agregar Usuario</button>
            </div>
        </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
