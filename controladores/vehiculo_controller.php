<?php
require_once '../config/conexion.php';
require_once '../models/vehiculo.php';

header('Content-Type: application/json');

try {
    $conexion = getConexion();
    $vehiculo = new Vehiculo($conexion);
    
    if (!isset($_POST['accion'])) {
        throw new Exception('Acción no especificada');
    }

    switch($_POST['accion']) {
        case 'insertar':
            try {
                // Validación de campos requeridos
                $camposRequeridos = ['marca', 'modelo', 'año', 'color', 'tipo_combustible', 
                                    'precio', 'estado', 'kilometraje', 'id_proveedor'];
                
                foreach ($camposRequeridos as $campo) {
                    if (empty($_POST[$campo])) {
                        throw new Exception("El campo $campo es obligatorio");
                    }
                }

                // Validación de datos
                $errores = validarDatosVehiculo($_POST);
                if (!empty($errores)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Errores de validación',
                        'errors' => $errores
                    ]);
                    exit;
                }

                // Debug
                error_log("Datos recibidos: " . print_r($_POST, true));

                // Manejo de la imagen
                $foto = null;
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $foto = manejarSubidaImagen($_FILES['foto']);
                    $_POST['foto'] = $foto;
                }

                $resultado = $vehiculo->crearVehiculo($_POST);
                
                if ($resultado) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Vehículo registrado correctamente',
                        'id' => $resultado
                    ]);
                } else {
                    throw new Exception('Error al crear el vehículo');
                }
            } catch (Exception $e) {
                error_log("Error al insertar vehículo: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'listar':
            $filtros = [
                'marca' => $_POST['marca'] ?? null,
                'precio_min' => $_POST['precio_min'] ?? null,
                'precio_max' => $_POST['precio_max'] ?? null,
                'disponible' => $_POST['disponible'] ?? null
            ];
            
            $vehiculos = $vehiculo->obtenerVehiculos($filtros);
            echo json_encode($vehiculos);
            break;

        case 'obtener':
            if (!isset($_POST['id_vehiculo'])) {
                throw new Exception('ID de vehículo no especificado');
            }

            $id = filter_var($_POST['id_vehiculo'], FILTER_VALIDATE_INT);
            if ($id === false) {
                throw new Exception('ID de vehículo inválido');
            }

            error_log("Obteniendo vehículo con ID: $id");
            
            $resultado = $vehiculo->obtenerVehiculo($id);
            
            if (!$resultado) {
                throw new Exception('No se encontró el vehículo');
            }

            error_log("Datos del vehículo encontrado: " . print_r($resultado, true));
            
            // Enviar respuesta sin wrapper
            echo json_encode($resultado);
            break;

        case 'actualizar':
            if (empty($_POST['id_vehiculo'])) {
                throw new Exception('ID de vehículo no especificado');
            }

            // Manejo de la imagen si se sube una nueva
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $_POST['foto'] = manejarSubidaImagen($_FILES['foto']);
            }

            error_log("Datos a actualizar: " . print_r($_POST, true));
            
            $resultado = $vehiculo->actualizarVehiculo($_POST['id_vehiculo'], $_POST);
            echo json_encode([
                'success' => true,
                'message' => 'Vehículo actualizado correctamente'
            ]);
            break;

        case 'eliminar':
            if (!isset($_POST['id_vehiculo'])) {
                throw new Exception('ID de vehículo no especificado');
            }
            
            $resultado = $vehiculo->eliminarVehiculo($_POST['id_vehiculo']);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Vehículo eliminado correctamente'
            ]);
            break;

        case 'cambiar_disponibilidad':
            if (!isset($_POST['id_vehiculo']) || !isset($_POST['disponible'])) {
                throw new Exception('Parámetros incompletos');
            }
            
            $resultado = $vehiculo->cambiarDisponibilidad(
                $_POST['id_vehiculo'], 
                $_POST['disponible']
            );
            
            echo json_encode([
                'success' => $resultado,
                'message' => 'Disponibilidad actualizada correctamente'
            ]);
            break;

        case 'estadisticas':
            try {
                $stats = $vehiculo->obtenerEstadisticas();
                echo json_encode([
                    'success' => true,
                    'total' => $stats['total'],
                    'disponibles' => $stats['disponibles'],
                    'precioPromedio' => $stats['precioPromedio'],
                    'porTipoCombustible' => $stats['porTipoCombustible'],
                    'porEstado' => $stats['porEstado']
                ]);
            } catch (Exception $e) {
                error_log("Error en estadísticas: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al obtener estadísticas'
                ]);
            }
            break;

        case 'reporte_precio':
            try {
                $reportePrecio = $vehiculo->obtenerReportePrecio();
                echo json_encode([
                    'success' => true,
                    'data' => $reportePrecio
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'filtro_avanzado':
            try {
                $filtros = [
                    'marca' => $_POST['marca'] ?? null,
                    'modelo' => $_POST['modelo'] ?? null,
                    'año_min' => $_POST['año_min'] ?? null,
                    'año_max' => $_POST['año_max'] ?? null,
                    'tipo_combustible' => $_POST['tipo_combustible'] ?? null,
                    'estado' => $_POST['estado'] ?? null,
                    'precio_min' => $_POST['precio_min'] ?? null,
                    'precio_max' => $_POST['precio_max'] ?? null,
                    'kilometraje_max' => $_POST['kilometraje_max'] ?? null
                ];

                $resultados = $vehiculo->busquedaAvanzada($filtros);
                echo json_encode([
                    'success' => true,
                    'data' => $resultados
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'exportar_excel':
            try {
                $datos = $vehiculo->obtenerVehiculos([]);
                
                // Cabeceras para descarga de Excel
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="vehiculos.xls"');
                
                // Crear tabla Excel
                echo "<table border='1'>";
                // Cabeceras
                echo "<tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Combustible</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Kilometraje</th>
                        <th>Proveedor</th>
                    </tr>";
                
                // Datos
                foreach ($datos as $vehiculo) {
                    echo "<tr>";
                    echo "<td>{$vehiculo['id_vehiculo']}</td>";
                    echo "<td>{$vehiculo['marca']}</td>";
                    echo "<td>{$vehiculo['modelo']}</td>";
                    echo "<td>{$vehiculo['año']}</td>";
                    echo "<td>{$vehiculo['color']}</td>";
                    echo "<td>{$vehiculo['tipo_combustible']}</td>";
                    echo "<td>{$vehiculo['precio']}</td>";
                    echo "<td>{$vehiculo['estado']}</td>";
                    echo "<td>{$vehiculo['kilometraje']}</td>";
                    echo "<td>{$vehiculo['proveedor']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
                exit;
            } catch (Exception $e) {
                error_log("Error en exportación: " . $e->getMessage());
                echo "Error al exportar datos";
            }
            break;

        case 'generar_pdf':
            try {
                require_once('../classes/VehiculosPDF.php');
                
                // Obtener datos
                $vehiculos = $vehiculo->obtenerVehiculos([]);
                $stats = $vehiculo->obtenerEstadisticas();
                
                // Crear PDF
                $pdf = new VehiculosPDF('L');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Cabecera de la tabla
                $header = ['ID', 'Marca', 'Modelo', 'Año', 'Color', 'Combustible', 'Precio', 'Estado', 'Kilometraje'];

                // Preparar datos
                $data = array_map(function($v) {
                    return [
                        $v['id_vehiculo'],
                        $v['marca'],
                        $v['modelo'],
                        $v['año'],
                        $v['color'],
                        $v['tipo_combustible'],
                        number_format($v['precio'], 2, ',', '.') . ' €',
                        $v['estado'],
                        number_format($v['kilometraje'], 0, ',', '.') . ' km'
                    ];
                }, $vehiculos);

                // Generar tabla
                $pdf->TablaVehiculos($header, $data);
                
                // Agregar estadísticas
                $pdf->EstadisticasSeccion($stats);

                // Generar y descargar PDF
                $pdf->Output('vehiculos_' . date('Y-m-d') . '.pdf', 'D');
                exit;
                
            } catch (Exception $e) {
                error_log("Error al generar PDF: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al generar el PDF: ' . $e->getMessage()
                ]);
            }
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    error_log("Error en controlador: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function manejarSubidaImagen($archivo) {
    $directorio = "../uploads/vehiculos/";
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $nombreArchivo = uniqid() . '.' . $extension;
    $rutaCompleta = $directorio . $nombreArchivo;

    // Validar tipo de archivo
    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($extension), $tiposPermitidos)) {
        throw new Exception('Tipo de archivo no permitido');
    }

    // Validar tamaño (máximo 5MB)
    if ($archivo['size'] > 5 * 1024 * 1024) {
        throw new Exception('El archivo excede el tamaño máximo permitido (5MB)');
    }

    if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
        throw new Exception('Error al subir la imagen');
    }

    return $nombreArchivo;
}

function validarDatosVehiculo($datos) {
    $errores = [];
    
    if (empty($datos['marca'])) {
        $errores[] = "La marca es obligatoria";
    }
    if (empty($datos['modelo'])) {
        $errores[] = "El modelo es obligatorio";
    }
    if (empty($datos['año']) || $datos['año'] < 1900 || $datos['año'] > date('Y')) {
        $errores[] = "El año debe estar entre 1900 y el año actual";
    }
    if (empty($datos['color'])) {
        $errores[] = "El color es obligatorio";
    }
    if (!in_array($datos['tipo_combustible'], ['Gasolina', 'Diésel', 'Eléctrico', 'Híbrido'])) {
        $errores[] = "Tipo de combustible no válido";
    }
    if (empty($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] < 0) {
        $errores[] = "El precio debe ser un número positivo";
    }
    if (!in_array($datos['estado'], ['Nuevo', 'Usado'])) {
        $errores[] = "Estado no válido";
    }
    if (!is_numeric($datos['kilometraje']) || $datos['kilometraje'] < 0) {
        $errores[] = "El kilometraje debe ser un número positivo";
    }
    if (empty($datos['id_proveedor'])) {
        $errores[] = "Debe seleccionar un proveedor";
    }
    
    return $errores;
}