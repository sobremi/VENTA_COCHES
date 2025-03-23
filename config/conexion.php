<?php
function getConexion() {
    $host = "localhost";
    $dbname = "consecionario";
    $usuario = "root";
    $password = "";

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $usuario, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->exec("SET CHARACTER SET utf8");
        return $conexion;
    } catch(PDOException $e) {
        throw new Exception("Error de conexión: " . $e->getMessage());
    }
}