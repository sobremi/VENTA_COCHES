<?php
class Usuario {
    private $db;
    public function __construct() {
        $this->db = $conexion;
    }
    public function verificarCredenciales($usuario, $contrasena) {
        $sql = "SELECT * FROM Usuarios WHERE usuario = :usuario AND contrasena = :contrasena";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['usuario' => $usuario, 'contrasena' => md5($contrasena)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearUsuario($usuario, $contrasena) {
        $sql = "INSERT INTO Usuarios (usuario, contrasena) VALUES (:usuario, :contrasena)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['usuario' => $usuario, 'contrasena' => md5($contrasena)]);
        return $this->conexion->lastInsertId();
    }
}