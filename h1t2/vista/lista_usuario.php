<?php
require_once '../controlador/UsuariosController.php';

// Creamos una instancia del controlador para gestionar los usuarios
$controller = new UsuariosController();

// Obtenemos la lista de usuarios desde el controlador
$usuarios = $controller->listarUsuario();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Usuarios</h2>
        <a href="../index.php" class="btn btn-primary">Volver al Menu Principal</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Apellidos del Usuario</th>
                    <th>Correo</th>
                    <th>Edad</th>
                    <th>Plan Base</th>
                    <th>Paquetes Adicionales</th>
                    <th>Duración</th>
                    <th>Valor Mensual Total</th>
                    <th>Acciones</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                    <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['apellidos']; ?></td>
                        <td><?php echo $usuario['correo']; ?></td>
                        <td><?php echo $usuario['edad']; ?></td>
                        <td><?php echo $usuario['nombre_plan']; ?></td> 
                        <td><?php echo $usuario['paquetes_adicionales'] ?? 'Ninguno'; ?></td>
                        <td><?php echo $usuario['duracion_suscripcion']; ?></td>
                        <td><?php echo number_format($usuario['costo_total'], 2) . ' €'; ?></td>
                        <td>
                            <a href="editar_usuario.php?id_usuario=<?php echo $usuario['id_usuario']; ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar_usuario.php?id_usuario=<?php echo $usuario['id_usuario']; ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
