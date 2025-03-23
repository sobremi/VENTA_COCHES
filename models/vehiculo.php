<?php
class Vehiculo {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function crearVehiculo($datos) {
        try {
            error_log("Iniciando creación de vehículo con datos: " . print_r($datos, true));

            $sql = "INSERT INTO Vehiculos (
                marca, 
                modelo, 
                año, 
                color,
                tipo_combustible,
                precio, 
                estado,
                kilometraje, 
                fecha_ingreso, 
                id_proveedor,
                disponible,
                foto
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_DATE, ?, 1, ?)";

            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                $datos['marca'],
                $datos['modelo'],
                $datos['año'],
                $datos['color'],
                $datos['tipo_combustible'],
                $datos['precio'],
                $datos['estado'],
                $datos['kilometraje'],
                $datos['id_proveedor'],
                $datos['foto'] ?? null
            ]);

            if (!$resultado) {
                error_log("Error SQL: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Error al insertar el vehículo en la base de datos");
            }

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en crearVehiculo: " . $e->getMessage());
            throw new Exception("Error al crear el vehículo: " . $e->getMessage());
        }
    }

    public function obtenerVehiculos($filtros = []) {
        try {
            $sql = "SELECT v.*, p.nombre_empresa as proveedor 
                    FROM vehiculos v 
                    LEFT JOIN proveedores p ON v.id_proveedor = p.id_proveedor 
                    WHERE 1=1";
            $params = [];

            if (!empty($filtros['marca'])) {
                $sql .= " AND v.marca LIKE ?";
                $params[] = "%{$filtros['marca']}%";
            }

            if (!empty($filtros['precio_min'])) {
                $sql .= " AND v.precio >= ?";
                $params[] = $filtros['precio_min'];
            }

            if (!empty($filtros['precio_max'])) {
                $sql .= " AND v.precio <= ?";
                $params[] = $filtros['precio_max'];
            }

            if (isset($filtros['disponible'])) {
                $sql .= " AND v.disponible = ?";
                $params[] = $filtros['disponible'];
            }

            $sql .= " ORDER BY v.fecha_ingreso DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener vehículos: " . $e->getMessage());
        }
    }

    public function obtenerVehiculo($id) {
        try {
            // Añadimos más campos a la consulta
            $sql = "SELECT 
                    v.*,
                    p.nombre_empresa as proveedor,
                    p.id_proveedor
                    FROM vehiculos v 
                    LEFT JOIN proveedores p ON v.id_proveedor = p.id_proveedor 
                    WHERE v.id_vehiculo = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$resultado) {
                error_log("No se encontró el vehículo con ID: " . $id);
                return null;
            }
            
            // Asegurarnos de que todos los campos necesarios existan
            $resultado = array_merge([
                'marca' => '',
                'modelo' => '',
                'anio' => 0,
                'precio' => 0,
                'kilometraje' => 0,
                'id_proveedor' => null,
                'disponible' => 1,
                'foto' => null
            ], $resultado);
            
            // Convertir tipos de datos
            $resultado['anio'] = (int)$resultado['anio'];
            $resultado['precio'] = (float)$resultado['precio'];
            $resultado['kilometraje'] = (int)$resultado['kilometraje'];
            $resultado['id_proveedor'] = (int)$resultado['id_proveedor'];
            $resultado['disponible'] = (bool)$resultado['disponible'];
            
            error_log("Datos obtenidos del vehículo: " . print_r($resultado, true));
            
            return $resultado;
        } catch (PDOException $e) {
            error_log("Error en obtenerVehiculo: " . $e->getMessage());
            throw new Exception("Error al obtener el vehículo: " . $e->getMessage());
        }
    }

    public function actualizarVehiculo($id, $datos) {
        try {
            $campos = [];
            $valores = [];

            // Campos según tu estructura de base de datos
            $camposPosibles = [
                'marca', 'modelo', 'año', 'color', 'tipo_combustible',
                'precio', 'estado', 'kilometraje', 'id_proveedor', 'disponible', 'foto'
            ];

            foreach ($camposPosibles as $campo) {
                if (isset($datos[$campo])) {
                    $campos[] = "$campo = ?";
                    $valores[] = $datos[$campo];
                }
            }

            // Añadir el ID al final del array de valores
            $valores[] = $id;

            $sql = "UPDATE Vehiculos SET " . implode(", ", $campos) . " WHERE id_vehiculo = ?";
            
            error_log("SQL de actualización: " . $sql);
            error_log("Valores: " . print_r($valores, true));

            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute($valores);

            if (!$resultado) {
                error_log("Error en la actualización: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Error al actualizar el vehículo");
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error en actualizarVehiculo: " . $e->getMessage());
            throw new Exception("Error al actualizar el vehículo: " . $e->getMessage());
        }
    }

    public function eliminarVehiculo($id) {
        try {
            // Verificar si el vehículo está en alguna venta
            $sql = "SELECT COUNT(*) FROM ventas WHERE id_vehiculo = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("No se puede eliminar el vehículo porque está asociado a una venta");
            }

            // Eliminar el vehículo
            $sql = "DELETE FROM vehiculos WHERE id_vehiculo = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el vehículo: " . $e->getMessage());
        }
    }

    public function cambiarDisponibilidad($id, $disponible) {
        try {
            $sql = "UPDATE vehiculos SET disponible = ? WHERE id_vehiculo = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$disponible, $id]);
        } catch (PDOException $e) {
            throw new Exception("Error al cambiar la disponibilidad: " . $e->getMessage());
        }
    }

    public function obtenerEstadisticas() {
        try {
            // Total y disponibles
            $sqlBasico = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN disponible = 1 THEN 1 ELSE 0 END) as disponibles,
                AVG(precio) as precioPromedio
                FROM Vehiculos";
            $stmtBasico = $this->db->query($sqlBasico);
            $statsBasicas = $stmtBasico->fetch(PDO::FETCH_ASSOC);

            // Por tipo de combustible
            $sqlCombustible = "SELECT 
                tipo_combustible,
                COUNT(*) as cantidad 
                FROM Vehiculos 
                GROUP BY tipo_combustible";
            $stmtCombustible = $this->db->query($sqlCombustible);
            $statsCombustible = $stmtCombustible->fetchAll(PDO::FETCH_ASSOC);

            // Por estado
            $sqlEstado = "SELECT 
                estado,
                COUNT(*) as cantidad 
                FROM Vehiculos 
                GROUP BY estado";
            $stmtEstado = $this->db->query($sqlEstado);
            $statsEstado = $stmtEstado->fetchAll(PDO::FETCH_ASSOC);

            return [
                'total' => $statsBasicas['total'],
                'disponibles' => $statsBasicas['disponibles'],
                'precioPromedio' => $statsBasicas['precioPromedio'],
                'porTipoCombustible' => $statsCombustible,
                'porEstado' => $statsEstado
            ];
        } catch (PDOException $e) {
            error_log("Error en obtenerEstadisticas: " . $e->getMessage());
            throw new Exception("Error al obtener estadísticas");
        }
    }

    
    public function busquedaAvanzada($filtros) {
        try {
            $sql = "SELECT v.*, p.nombre_empresa as proveedor 
                    FROM Vehiculos v 
                    LEFT JOIN Proveedores p ON v.id_proveedor = p.id_proveedor 
                    WHERE 1=1";
            $params = [];

            if (!empty($filtros['marca'])) {
                $sql .= " AND v.marca LIKE ?";
                $params[] = "%{$filtros['marca']}%";
            }

            if (!empty($filtros['modelo'])) {
                $sql .= " AND v.modelo LIKE ?";
                $params[] = "%{$filtros['modelo']}%";
            }

            if (!empty($filtros['año_min'])) {
                $sql .= " AND v.año >= ?";
                $params[] = $filtros['año_min'];
            }

            if (!empty($filtros['año_max'])) {
                $sql .= " AND v.año <= ?";
                $params[] = $filtros['año_max'];
            }

            if (!empty($filtros['tipo_combustible'])) {
                $sql .= " AND v.tipo_combustible = ?";
                $params[] = $filtros['tipo_combustible'];
            }

            if (!empty($filtros['estado'])) {
                $sql .= " AND v.estado = ?";
                $params[] = $filtros['estado'];
            }

            if (!empty($filtros['precio_min'])) {
                $sql .= " AND v.precio >= ?";
                $params[] = $filtros['precio_min'];
            }

            if (!empty($filtros['precio_max'])) {
                $sql .= " AND v.precio <= ?";
                $params[] = $filtros['precio_max'];
            }

            if (!empty($filtros['kilometraje_max'])) {
                $sql .= " AND v.kilometraje <= ?";
                $params[] = $filtros['kilometraje_max'];
            }

            $sql .= " ORDER BY v.fecha_ingreso DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en busquedaAvanzada: " . $e->getMessage());
            throw new Exception("Error al realizar la búsqueda avanzada");
        }
    }

    public function obtenerReportePrecio() {
        try {
            $sql = "SELECT 
                        MIN(precio) as precio_min,
                        MAX(precio) as precio_max,
                        AVG(precio) as precio_promedio,
                        COUNT(*) as total_vehiculos,
                        SUM(CASE WHEN disponible = 1 THEN 1 ELSE 0 END) as disponibles
                    FROM Vehiculos";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerReportePrecio: " . $e->getMessage());
            throw new Exception("Error al generar el reporte de precios");
        }
    }
}