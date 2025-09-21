<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function index() {
        return $this->usuarioModel->obtenerUsuarios();
    }

    public function crear($nombre, $usuario, $contrasena, $rol) {
        return $this->usuarioModel->crearUsuario($nombre, $usuario, $contrasena, $rol);
    }

    public function eliminar($id) {
        return $this->usuarioModel->eliminarUsuario($id);
    }
}
?>
