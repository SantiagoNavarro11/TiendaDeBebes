<?php
session_start();

// Si no hay sesión activa, ir al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /tienda_bebes/views/login.php");
    exit();
}

$nombre = $_SESSION['usuario_nombre'];
$rol = $_SESSION['usuario_rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - Tienda Bebés</title>
    <link rel="stylesheet" href="/tienda_bebes/css/estilo.css">
</head>
<body>
    <div class="panel">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h1>
        <p>Rol: <strong><?php echo htmlspecialchars($rol); ?></strong></p>

        <nav class="menu">
            <?php if ($rol === 'admin'): ?>
                <a class="btn" href="/tienda_bebes/views/usuarios.php">👥 Gestión de Usuarios</a>
                <a class="btn" href="/tienda_bebes/views/ropa.php">👗 Gestionar Ropa</a>
            <?php else: ?>
                <a class="btn" href="/tienda_bebes/views/ropa.php">🛍️ Ver Ropa</a>
            <?php endif; ?>

            <a class="btn logout" href="/tienda_bebes/controllers/LoginController.php?action=logout">🚪 Cerrar sesión</a>
        </nav>
    </div>
</body>
</html>
