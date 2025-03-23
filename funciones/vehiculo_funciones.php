<?php
require_once '../config/conexion.php';

function listarVehiculos() {
    $conexion = getConexion();
    $query = "SELECT * FROM Vehiculos";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertarVehiculo($datos) {
    $conexion = getConexion();
    $query = "INSERT INTO Vehiculos (marca, modelo, año, color, tipo_combustible, precio, estado, kilometraje, fecha_ingreso, foto, id_proveedor, disponible) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    return $stmt->execute($datos);
}

function actualizarVehiculo($datos) {
    $conexion = getConexion();
    $query = "UPDATE Vehiculos SET 
              marca = ?, modelo = ?, año = ?, color = ?, tipo_combustible = ?, 
              precio = ?, estado = ?, kilometraje = ?, fecha_ingreso = ?, 
              foto = ?, id_proveedor = ?, disponible = ? 
              WHERE id_vehiculo = ?";
    $stmt = $conexion->prepare($query);
    return $stmt->execute($datos);
}

function eliminarVehiculo($id) {
    $conexion = getConexion();
    $query = "DELETE FROM Vehiculos WHERE id_vehiculo = ?";
    $stmt = $conexion->prepare($query);
    return $stmt->execute([$id]);
}

function obtenerVehiculo($id) {
    $conexion = getConexion();
    $query = "SELECT * FROM Vehiculos WHERE id_vehiculo = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}