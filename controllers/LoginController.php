<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

class LoginController {

    public function login($email, $password) {
        $u = new Usuario();
        $res = $u->validarLogin($email, $password);

        if ($res) {
            // Guardar en sesión
            $_SESSION['usuario_id'] = $res['id'];
            $_SESSION['usuario_nombre'] = $res['nombre'];
            $_SESSION['usuario_rol'] = $res['rol'];

            // Redirigir al panel principal (index.php)
            header("Location: /tienda_bebes/index.php");
            exit();
        } else {
            // Falló: volver al login con error
            header("Location: /tienda_bebes/views/login.php?error=1");
            exit();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: /tienda_bebes/views/login.php");
        exit();
    }
}

// Manejo de peticiones directas al controlador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $controller = new LoginController();
    $controller->login($_POST['email'] ?? '', $_POST['password'] ?? '');
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller = new LoginController();
    $controller->logout();
}
?>
