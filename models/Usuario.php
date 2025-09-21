<?php
require_once __DIR__ . '/../config/db.php';

class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Validar login (texto plano)
    public function validarLogin($email, $password) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && isset($usuario['password']) && $usuario['password'] === $password) {
            return $usuario;
        }
        return false;
    }

    // Obtener todos los usuarios (para listar)
    public function getAll() {
        $sql = "SELECT id, nombre, email, rol FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear usuario (texto plano)
    public function crear($nombre, $email, $password, $rol) {
        $sql = "INSERT INTO " . $this->table . " (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $password,
            ':rol' => $rol
        ]);
    }

    // Eliminar usuario por id
    public function eliminar($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
