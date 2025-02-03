<?php
require_once '../controlador/UsuariosController.php';

// Creamos una instancia del controlador para gestionar usuarios
$controller = new UsuariosController();
$error_message = ''; // Variable para almacenar mensajes de error
$success_message = ''; // Variable para almacenar mensajes de exito
$usuario = null; // Variable para almacenar los datos del usuario

// Verificamos si ha enviado un ID de usuario mediante GET
if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];
    $usuario = $controller->obtenerUsuarioPorId($id_usuario);
}

// Verificamos si el formulario ha sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos los datos del formulario
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $edad = $_POST['edad'];
    $id_plan = $_POST['id_plan'];
    $paquetes = $_POST['paquetes'] ?? []; // Utilizamos null para el caso de que no se seleccionen paquetes.
    $duracion_suscripcion = $_POST['duracion_suscripcion'];

    // // Validamos las restricciones antes de actualizar el usuario
    if ($edad < 18 && (!in_array(3, $paquetes) || count($paquetes) > 1)) {
        $error_message = "Los usuarios menores de 18 años solo pueden contratar el Pack Infantil.";
    } elseif ($id_plan == 1 && count($paquetes) > 1) {
        $error_message = "Los usuarios del Plan Basico solo pueden seleccionar un paquete adicional.";
    } elseif (in_array(1, $paquetes) && $duracion_suscripcion != 'Anual') {
        $error_message = "El Pack Deporte solo puede ser contratado con una duracion anual.";
    } else {
        $updated = $controller->actualizarUsuario($id_usuario, $nombre, $apellidos, $correo, $edad, $id_plan, $paquetes, $duracion_suscripcion);
        if (!$updated) {
            $error_message = "Error al actualizar usuario. Por favor, verifica los datos.";
        } else {
            $success_message = "Usuario actualizado con exito.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Usuario</h2>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form action="editar_usuario.php?id_usuario=<?php echo $id_usuario; ?>" method="POST" class="row g-3">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $usuario['apellidos']; ?>" required>
            </div>
            <div class="col-md-6">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required>
            </div>
            <div class="col-md-6">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" class="form-control" id="edad" name="edad" value="<?php echo $usuario['edad']; ?>" required>
            </div>
            <div class="col-md-6">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" value="<?php echo $usuario['edad']; ?>" required>
            </div>
            <div class="col-md-6">
                <label for="id_plan" class="form-label">Plan Base</label>
                <select class="form-control" id="id_plan" name="id_plan" required>
                    <option value="1" <?php echo ($usuario['id_plan'] == 1) ? 'selected' : ''; ?>>Básico</option>
                    <option value="2" <?php echo ($usuario['id_plan'] == 2) ? 'selected' : ''; ?>>Estándar</option>
                    <option value="3" <?php echo ($usuario['id_plan'] == 3) ? 'selected' : ''; ?>>Premium</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Paquetes Adicionales</label>
                <?php
                $paquetes_usuario = $controller->obtenerPaquetesPorUsuario($id_usuario);
                $paquetes_seleccionados = array_column($paquetes_usuario, 'id_paquete');
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paquetes[]" value="1" id="deporte" <?php echo (in_array(1, $paquetes_seleccionados)) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="deporte">Deporte</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paquetes[]" value="2" id="cine" <?php echo (in_array(2, $paquetes_seleccionados)) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="cine">Cine</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paquetes[]" value="3" id="infantil" <?php echo (in_array(3, $paquetes_seleccionados)) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="infantil">Infantil</label>
                </div>
            </div>
            <div class="col-md-6">
                <label for="duracion_suscripcion" class="form-label">Duración de la Suscripción</label>
                <select class="form-control" id="duracion_suscripcion" name="duracion_suscripcion" required>
                    <option value="Mensual" <?php echo ($usuario['duracion_suscripcion'] == 'Mensual') ? 'selected' : ''; ?>>Mensual</option>
                    <option value="Anual" <?php echo ($usuario['duracion_suscripcion'] == 'Anual') ? 'selected' : ''; ?>>Anual</option>
                </select>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button><br><br>
                <a href="lista_usuario.php" class="btn btn-primary">Volver a la Lista de Usuarios</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
