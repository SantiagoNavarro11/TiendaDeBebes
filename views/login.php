<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    // Si ya está logueado, vamos al panel principal
    header("Location: /tienda_bebes/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Tienda Bebés</title>
    <link rel="stylesheet" href="/tienda_bebes/css/estilo.css">
</head>
<body class="login-body">
    <div class="login-container">
        <h2 class="login-title">👶 Tienda de Bebés</h2>
        <p class="login-subtitle">Bienvenido, por favor inicia sesión</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert">⚠️ Usuario o contraseña incorrectos</div>
        <?php endif; ?>

        <form action="/tienda_bebes/controllers/LoginController.php" method="POST" class="login-form">
            <input type="hidden" name="action" value="login">

            <label for="email">Correo</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>

            <button type="submit" class="btn-primary">Ingresar</button>
        </form>

        <p class="login-footer">¿No tienes cuenta? 👉 Crea usuarios desde el panel de Admin.</p>
    </div>
</body>
</html>
