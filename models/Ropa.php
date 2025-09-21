<?php
class Ropa {
    private $db;
    private $table = 'ropa_bebe';

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerTodos() {
        $sql = "SELECT id, nombre, precio, stock FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT id, nombre, precio, stock FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar($nombre, $precio, $stock) {
        $sql = "INSERT INTO {$this->table} (nombre, precio, stock) VALUES (:nombre, :precio, :stock)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':stock'  => $stock
        ]);
    }

    public function actualizar($id, $nombre, $precio, $stock) {
        $sql = "UPDATE {$this->table} SET nombre = :nombre, precio = :precio, stock = :stock WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':stock'  => $stock
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
