<?php
class Venta {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function crearVenta($datos) {
        try {
            $sql = "INSERT INTO ventas (
                id_cliente, 
                id_vehiculo, 
                fecha_venta, 
                precio_venta, 
                metodo_pago, 
                estado_venta, 
                observaciones
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['id_cliente'],
                $datos['id_vehiculo'],
                $datos['fecha_venta'],
                $datos['precio_venta'],
                $datos['metodo_pago'],
                $datos['estado_venta'],
                $datos['observaciones']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al crear la venta: " . $e->getMessage());
        }
    }

    public function obtenerVentas() {
        try {
            $sql = "SELECT v.*, 
                    c.nombre as nombre_cliente, c.apellido as apellido_cliente,
                    vh.marca, vh.modelo
                    FROM ventas v
                    LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                    LEFT JOIN vehiculos vh ON v.id_vehiculo = vh.id_vehiculo
                    ORDER BY v.fecha_venta DESC";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener las ventas: " . $e->getMessage());
        }
    }

    public function obtenerVenta($id) {
        try {
            $sql = "SELECT * FROM ventas WHERE id_venta = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la venta: " . $e->getMessage());
        }
    }

    public function actualizarVenta($id, $datos) {
        try {
            $sql = "UPDATE ventas SET 
                    id_cliente = ?, 
                    id_vehiculo = ?, 
                    fecha_venta = ?,
                    precio_venta = ?,
                    metodo_pago = ?,
                    estado_venta = ?,
                    observaciones = ?
                    WHERE id_venta = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['id_cliente'],
                $datos['id_vehiculo'],
                $datos['fecha_venta'],
                $datos['precio_venta'],
                $datos['metodo_pago'],
                $datos['estado_venta'],
                $datos['observaciones'],
                $id
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar la venta: " . $e->getMessage());
        }
    }

    public function eliminarVenta($id) {
        try {
            $sql = "DELETE FROM ventas WHERE id_venta = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar la venta: " . $e->getMessage());
        }
    }
}