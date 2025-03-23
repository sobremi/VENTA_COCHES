<?php
require_once '../config/conexion.php';
require_once '../models/venta.php';

header('Content-Type: application/json');

try {
    $conexion = getConexion();
    $venta = new Venta($conexion);
    
    if (!isset($_POST['accion'])) {
        throw new Exception('Acción no especificada');
    }

    switch($_POST['accion']) {
        case 'insertar':
            // Validar campos requeridos
            if (empty($_POST['id_cliente']) || empty($_POST['id_vehiculo']) || 
                empty($_POST['fecha_venta']) || empty($_POST['precio_venta'])) {
                throw new Exception('Faltan campos requeridos');
            }

            $resultado = $venta->crearVenta($_POST);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Venta registrada correctamente'
            ]);
            break;

        case 'listar':
            $ventas = $venta->obtenerVentas();
            echo json_encode($ventas);
            break;

        case 'obtener':
            if (!isset($_POST['id_venta'])) {
                throw new Exception('ID de venta no especificado');
            }
            $resultado = $venta->obtenerVenta($_POST['id_venta']);
            echo json_encode($resultado);
            break;

        case 'actualizar':
            if (!isset($_POST['id_venta'])) {
                throw new Exception('ID de venta no especificado');
            }
            $resultado = $venta->actualizarVenta($_POST['id_venta'], $_POST);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Venta actualizada correctamente'
            ]);
            break;

        case 'eliminar':
            if (!isset($_POST['id_venta'])) {
                throw new Exception('ID de venta no especificado');
            }
            $resultado = $venta->eliminarVenta($_POST['id_venta']);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Venta eliminada correctamente'
            ]);
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