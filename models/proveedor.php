<?php
class Proveedor {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function crearProveedor($datos) {
        try {
            $sql = "INSERT INTO proveedores (
                nombre_empresa, 
                nif,
                contacto_nombre, 
                telefono, 
                email, 
                direccion
            ) VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                $datos['nombre_empresa'],
                $datos['nif'],
                $datos['contacto_nombre'],
                $datos['telefono'],
                $datos['email'],
                $datos['direccion']
            ]);

            if (!$resultado) {
                error_log("Error PDO: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Error en la inserción: " . implode(" ", $stmt->errorInfo()));
            }

            return $resultado;
        } catch (PDOException $e) {
            error_log("Error PDO en crearProveedor: " . $e->getMessage());
            throw new Exception("Error al crear el proveedor: " . $e->getMessage());
        }
    }

    public function obtenerProveedores() {
        try {
            $sql = "SELECT * FROM proveedores ORDER BY id_proveedor DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener proveedores: " . $e->getMessage());
        }
    }

    public function obtenerProveedor($id) {
        try {
            $sql = "SELECT * FROM proveedores WHERE id_proveedor = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el proveedor: " . $e->getMessage());
        }
    }

    public function actualizarProveedor($id, $datos) {
        try {
            $sql = "UPDATE proveedores SET 
                    nombre_empresa = ?, 
                    nif = ?,
                    contacto_nombre = ?,
                    telefono = ?,
                    email = ?,
                    direccion = ?
                    WHERE id_proveedor = ?";
            
            error_log("SQL Update: " . $sql);
            error_log("Datos actualización: " . print_r($datos, true));
            
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                $datos['nombre_empresa'],
                $datos['nif'],
                $datos['contacto_nombre'] ?? null,
                $datos['telefono'] ?? null,
                $datos['email'] ?? null,
                $datos['direccion'] ?? null,
                $id
            ]);

            if (!$resultado) {
                error_log("Error PDO: " . print_r($stmt->errorInfo(), true));
            }

            return $resultado;
        } catch (PDOException $e) {
            error_log("Error en actualizarProveedor: " . $e->getMessage());
            throw new Exception("Error al actualizar el proveedor: " . $e->getMessage());
        }
    }

    public function eliminarProveedor($id) {
        try {
            // Verificar si tiene vehículos asociados
            $sql = "SELECT COUNT(*) FROM vehiculos WHERE id_proveedor = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("No se puede eliminar el proveedor porque tiene vehículos asociados");
            }

            $sql = "DELETE FROM proveedores WHERE id_proveedor = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el proveedor: " . $e->getMessage());
        }
    }

    public function buscarProveedores($termino) {
        try {
            $termino = "%$termino%";
            $sql = "SELECT * FROM proveedores 
                    WHERE nombre_empresa LIKE ? 
                    OR contacto_nombre LIKE ? 
                    OR email LIKE ? 
                    OR telefono LIKE ?
                    ORDER BY id_proveedor DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$termino, $termino, $termino, $termino]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al buscar proveedores: " . $e->getMessage());
        }
    }

    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE proveedores SET estado = ? WHERE id_proveedor = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (PDOException $e) {
            throw new Exception("Error al cambiar el estado del proveedor: " . $e->getMessage());
        }
    }

    public function obtenerEstadisticas() {
        try {
            $stats = [];
            
            // Total proveedores
            $sql = "SELECT COUNT(*) as total FROM proveedores";
            $stmt = $this->db->query($sql);
            $stats['total'] = $stmt->fetchColumn();

            // Proveedores activos/inactivos
            $sql = "SELECT estado, COUNT(*) as cantidad 
                    FROM proveedores 
                    GROUP BY estado";
            $stmt = $this->db->query($sql);
            $estados = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            $stats['activos'] = $estados[1] ?? 0;
            $stats['inactivos'] = $estados[0] ?? 0;

            // Proveedores con vehículos
            $sql = "SELECT COUNT(DISTINCT p.id_proveedor) as total,
                       AVG(v.precio) as precio_promedio
                FROM proveedores p
                JOIN vehiculos v ON p.id_proveedor = v.id_proveedor";
            $stmt = $this->db->query($sql);
            $vehiculos = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['con_vehiculos'] = $vehiculos['total'];
            $stats['precio_promedio'] = round($vehiculos['precio_promedio'], 2);

            return $stats;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }

    public function importarProveedores($archivo, $extension) {
        try {
            $proveedores = [];
            
            if ($extension === 'csv') {
                $handle = fopen($archivo, 'r');
                // Saltamos la cabecera
                fgetcsv($handle);
                
                while (($data = fgetcsv($handle)) !== false) {
                    $proveedores[] = [
                        'nombre_empresa' => $data[0],
                        'nif' => $data[1],
                        'contacto_nombre' => $data[2],
                        'telefono' => $data[3],
                        'email' => $data[4],
                        'direccion' => $data[5]
                    ];
                }
                fclose($handle);
            } else {
                require_once '../vendor/autoload.php';
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo);
                $worksheet = $spreadsheet->getActiveSheet();
                
                foreach ($worksheet->getRowIterator(2) as $row) {
                    $rowData = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    
                    if (!empty($rowData[0])) {
                        $proveedores[] = [
                            'nombre_empresa' => $rowData[0],
                            'nif' => $rowData[1],
                            'contacto_nombre' => $rowData[2],
                            'telefono' => $rowData[3],
                            'email' => $rowData[4],
                            'direccion' => $rowData[5]
                        ];
                    }
                }
            }

            $importados = 0;
            foreach ($proveedores as $datos) {
                if ($this->validarDatosProveedor($datos)) {
                    $this->crearProveedor($datos);
                    $importados++;
                }
            }

            return $importados;
        } catch (Exception $e) {
            throw new Exception("Error al importar proveedores: " . $e->getMessage());
        }
    }

    private function validarDatosProveedor($datos) {
        // Validación básica
        if (empty($datos['nombre_empresa'])) {
            return false;
        }

        // Validación de NIF
        if (!empty($datos['nif']) && !$this->validarNIF($datos['nif'])) {
            return false;
        }

        // Validación de email
        if (!empty($datos['email']) && !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Validación de teléfono (formato español)
        if (!empty($datos['telefono']) && !preg_match('/^[69][0-9]{8}$/', $datos['telefono'])) {
            return false;
        }

        return true;
    }
}