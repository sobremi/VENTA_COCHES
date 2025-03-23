<?php
class ReportesVehiculos {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function reporteGeneral() {
        try {
            $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN disponible = 1 THEN 1 ELSE 0 END) as disponibles,
                AVG(precio) as precio_promedio,
                MIN(precio) as precio_minimo,
                MAX(precio) as precio_maximo,
                COUNT(DISTINCT marca) as total_marcas,
                COUNT(DISTINCT modelo) as total_modelos
                FROM Vehiculos";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al generar reporte general: " . $e->getMessage());
        }
    }

    public function reportePorMarca() {
        try {
            $sql = "SELECT 
                marca,
                COUNT(*) as total,
                AVG(precio) as precio_promedio,
                SUM(CASE WHEN disponible = 1 THEN 1 ELSE 0 END) as disponibles
                FROM Vehiculos
                GROUP BY marca
                ORDER BY total DESC";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al generar reporte por marca: " . $e->getMessage());
        }
    }

    public function reporteVentasMensuales() {
        try {
            $sql = "SELECT 
                DATE_FORMAT(fecha_ingreso, '%Y-%m') as mes,
                COUNT(*) as total_ingresos,
                SUM(precio) as valor_total
                FROM Vehiculos
                GROUP BY DATE_FORMAT(fecha_ingreso, '%Y-%m')
                ORDER BY mes DESC
                LIMIT 12";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al generar reporte mensual: " . $e->getMessage());
        }
    }
}