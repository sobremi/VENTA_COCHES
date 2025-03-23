<?php
class Empleado {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function crearEmpleado($datos) {
        try {
            $sql = "INSERT INTO empleados (nombre, apellido, dni, cargo, telefono, fecha_contratacion) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['nombre'],
                $datos['apellido'],
                $datos['dni'],
                $datos['cargo'],
                $datos['telefono'],
                $datos['fecha_contratacion']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al crear el empleado: " . $e->getMessage());
        }
    }

    public function obtenerEmpleados() {
        try {
            $sql = "SELECT * FROM empleados ORDER BY id_empleado DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener empleados: " . $e->getMessage());
        }
    }

    public function obtenerEmpleado($id) {
        try {
            $sql = "SELECT * FROM empleados WHERE id_empleado = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el empleado: " . $e->getMessage());
        }
    }

    public function actualizarEmpleado($id, $datos) {
        try {
            $sql = "UPDATE empleados SET 
                    nombre = ?, 
                    apellido = ?, 
                    dni = ?,
                    cargo = ?,
                    telefono = ?,
                    fecha_contratacion = ?
                    WHERE id_empleado = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['nombre'],
                $datos['apellido'],
                $datos['dni'],
                $datos['cargo'],
                $datos['telefono'],
                $datos['fecha_contratacion'],
                $id
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el empleado: " . $e->getMessage());
        }
    }

    public function eliminarEmpleado($id) {
        try {
            $sql = "DELETE FROM empleados WHERE id_empleado = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el empleado: " . $e->getMessage());
        }
    }
}