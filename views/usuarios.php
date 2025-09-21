<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: /tienda_bebes/index.php");
    exit();
}

require_once __DIR__ . '/../models/Usuario.php';
$usuarioModel = new Usuario();

// Procesar creación simple (POST directo a este archivo)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_usuario'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password']; // texto plano (según tu requerimiento)
    $rol = $_POST['rol'];
    $usuarioModel->crear($nombre, $email, $password, $rol);
    header("Location: /tienda_bebes/views/usuarios.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $usuarioModel->eliminar((int)$_GET['eliminar']);
    header("Location: /tienda_bebes/views/usuarios.php");
    exit();
}

$usuarios = $usuarioModel->getAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Tienda Bebés</title>
    <link rel="stylesheet" href="/tienda_bebes/css/estilo.css">
</head>
<body>
    <div class="card">
        <h2>Gestión de Usuarios - Crear usuario</h2>
        <form method="POST">
            <input type="hidden" name="crear_usuario" value="1">
            <label>Nombre</label>
            <input type="text" name="nombre" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Contraseña</label>
            <input type="text" name="password" required>

            <label>Rol</label>
            <select name="rol">
                <option value="cliente">Cliente</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Crear</button>
        </form>

        <h3>Lista de usuarios</h3>
        <table>
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acción</th></tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo $u['rol']; ?></td>
                    <td>
                        <?php if ($u['id'] != $_SESSION['usuario_id']): // no permitir eliminarme a mí ?>
                            <a class="link-eliminar" href="?eliminar=<?php echo $u['id']; ?>" onclick="return confirm('Eliminar usuario?')">Eliminar</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><a href="/tienda_bebes/index.php">← Volver al panel</a></p>
    </div>
</body>
</html>
