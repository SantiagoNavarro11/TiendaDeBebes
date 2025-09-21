<?php
require_once __DIR__ . '/../models/Ropa.php';

class RopaController {
    private $ropaModel;

    public function __construct($db) {
        $this->ropaModel = new Ropa($db);
    }

    public function index() {
        return $this->ropaModel->obtenerTodos();
    }

    public function getById($id) {
        return $this->ropaModel->obtenerPorId($id);
    }

    public function guardar($nombre, $precio, $stock) {
        return $this->ropaModel->insertar($nombre, $precio, $stock);
    }

    public function actualizar($id, $nombre, $precio, $stock) {
        return $this->ropaModel->actualizar($id, $nombre, $precio, $stock);
    }

    public function eliminar($id) {
        return $this->ropaModel->eliminar($id);
    }
}
?>
