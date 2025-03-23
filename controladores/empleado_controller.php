<?php
require_once '../config/conexion.php';
require_once '../models/empleado.php';

header('Content-Type: application/json');

try {
    $conexion = getConexion();
    $empleado = new Empleado($conexion);
    
    if (!isset($_POST['accion'])) {
        throw new Exception('Acción no especificada');
    }

    switch($_POST['accion']) {
        case 'insertar':
            // Validar campos requeridos
            if (empty($_POST['nombre']) || empty($_POST['apellido']) || 
                empty($_POST['dni']) || empty($_POST['cargo'])) {
                throw new Exception('Faltan campos requeridos');
            }

            $resultado = $empleado->crearEmpleado($_POST);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Empleado registrado correctamente'
            ]);
            break;

        case 'listar':
            $empleados = $empleado->obtenerEmpleados();
            echo json_encode($empleados);
            break;

        case 'obtener':
            if (!isset($_POST['id_empleado'])) {
                throw new Exception('ID de empleado no especificado');
            }
            $resultado = $empleado->obtenerEmpleado($_POST['id_empleado']);
            echo json_encode($resultado);
            break;

        case 'actualizar':
            if (!isset($_POST['id_empleado'])) {
                throw new Exception('ID de empleado no especificado');
            }
            $resultado = $empleado->actualizarEmpleado($_POST['id_empleado'], $_POST);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Empleado actualizado correctamente'
            ]);
            break;

        case 'eliminar':
            if (!isset($_POST['id_empleado'])) {
                throw new Exception('ID de empleado no especificado');
            }
            $resultado = $empleado->eliminarEmpleado($_POST['id_empleado']);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Empleado eliminado correctamente'
            ]);
            break;

        case 'buscar':
            $termino = isset($_POST['termino']) ? $_POST['termino'] : '';
            $cargo = isset($_POST['cargo']) ? $_POST['cargo'] : '';
            
            $sql = "SELECT * FROM empleados WHERE 1=1";
            $params = [];
            
            if ($termino) {
                $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR dni LIKE ?)";
                $termino = "%$termino%";
                $params = array_merge($params, [$termino, $termino, $termino]);
            }
            
            if ($cargo) {
                $sql .= " AND cargo = ?";
                $params[] = $cargo;
            }
            
            $sql .= " ORDER BY id_empleado DESC";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}