<?php
require_once '../config/conexion.php';
require_once '../models/proveedor.php';

header('Content-Type: application/json');

try {
    $conexion = getConexion();
    $proveedor = new Proveedor($conexion);
    
    // Log para depuración
    error_log("Datos POST recibidos: " . print_r($_POST, true));
    
    if (!isset($_POST['accion'])) {
        throw new Exception('Acción no especificada');
    }

    switch($_POST['accion']) {
        case 'insertar':
            // Validación más flexible
            if (empty($_POST['nombre_empresa'])) {
                throw new Exception('El nombre de la empresa es obligatorio');
            }

            // Preparar datos para inserción
            $datosProveedor = [
                'nombre_empresa' => $_POST['nombre_empresa'],
                'contacto_nombre' => $_POST['contacto_nombre'] ?? null,
                'telefono' => $_POST['telefono'] ?? null,
                'email' => $_POST['email'] ?? null,
                'direccion' => $_POST['direccion'] ?? null
            ];

            // Log de datos a insertar
            error_log("Datos a insertar: " . print_r($datosProveedor, true));

            $resultado = $proveedor->crearProveedor($datosProveedor);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor registrado correctamente'
                ]);
            } else {
                throw new Exception('Error al registrar el proveedor');
            }
            break;

        case 'listar':
            $proveedores = $proveedor->obtenerProveedores();
            echo json_encode($proveedores);
            break;

        case 'obtener':
            if (!isset($_POST['id_proveedor'])) {
                throw new Exception('ID de proveedor no especificado');
            }
            $resultado = $proveedor->obtenerProveedor($_POST['id_proveedor']);
            
            if (!$resultado) {
                throw new Exception('Proveedor no encontrado');
            }
            
            echo json_encode($resultado);
            break;

        case 'actualizar':
            if (!isset($_POST['id_proveedor'])) {
                throw new Exception('ID de proveedor no especificado');
            }

            if (empty($_POST['nombre_empresa'])) {
                throw new Exception('El nombre de la empresa es obligatorio');
            }

            if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El formato del email no es válido');
            }

            $resultado = $proveedor->actualizarProveedor($_POST['id_proveedor'], $_POST);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Proveedor actualizado correctamente'
            ]);
            break;

        case 'eliminar':
            if (!isset($_POST['id_proveedor'])) {
                throw new Exception('ID de proveedor no especificado');
            }

            // Verificar si tiene vehículos asociados antes de eliminar
            $tieneVehiculos = $proveedor->tieneVehiculosAsociados($_POST['id_proveedor']);
            if ($tieneVehiculos) {
                throw new Exception('No se puede eliminar el proveedor porque tiene vehículos asociados');
            }

            $resultado = $proveedor->eliminarProveedor($_POST['id_proveedor']);
            echo json_encode([
                'success' => $resultado,
                'message' => 'Proveedor eliminado correctamente'
            ]);
            break;

        case 'buscar':
            $termino = isset($_POST['termino']) ? trim($_POST['termino']) : '';
            $proveedores = $termino ? $proveedor->buscarProveedores($termino) : $proveedor->obtenerProveedores();
            echo json_encode($proveedores);
            break;

        case 'exportar_excel':
            require_once '../vendor/autoload.php';
            $proveedores = $proveedor->obtenerProveedores();
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Encabezados
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Empresa');
            $sheet->setCellValue('C1', 'NIF');
            $sheet->setCellValue('D1', 'Contacto');
            $sheet->setCellValue('E1', 'Teléfono');
            $sheet->setCellValue('F1', 'Email');
            $sheet->setCellValue('G1', 'Estado');
        
            // Datos
            $row = 2;
            foreach ($proveedores as $p) {
                $sheet->setCellValue('A'.$row, $p['id_proveedor']);
                $sheet->setCellValue('B'.$row, $p['nombre_empresa']);
                $sheet->setCellValue('C'.$row, $p['nif']);
                $sheet->setCellValue('D'.$row, $p['contacto_nombre']);
                $sheet->setCellValue('E'.$row, $p['telefono']);
                $sheet->setCellValue('F'.$row, $p['email']);
                $sheet->setCellValue('G'.$row, $p['estado'] ? 'Activo' : 'Inactivo');
                $row++;
            }
        
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="proveedores.xlsx"');
            header('Cache-Control: max-age=0');
        
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            break;
        
        case 'exportar_pdf':
            require_once '../vendor/autoload.php';
            $proveedores = $proveedor->obtenerProveedores();
            
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Lista de Proveedores');
            $pdf->SetHeaderData('', '', 'Lista de Proveedores', '');
            $pdf->setHeaderFont(['helvetica', '', 12]);
            $pdf->setFooterFont(['helvetica', '', 8]);
            $pdf->AddPage();
        
            $html = '<table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empresa</th>
                        <th>NIF</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($proveedores as $p) {
                $html .= '<tr>
                    <td>'.$p['id_proveedor'].'</td>
                    <td>'.$p['nombre_empresa'].'</td>
                    <td>'.$p['nif'].'</td>
                    <td>'.$p['contacto_nombre'].'</td>
                    <td>'.$p['telefono'].'</td>
                    <td>'.$p['email'].'</td>
                    <td>'.($p['estado'] ? 'Activo' : 'Inactivo').'</td>
                </tr>';
            }
            
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output('proveedores.pdf', 'D');
            exit;
            break;

        case 'estadisticas':
            $stats = $proveedor->obtenerEstadisticas();
            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
            break;

        case 'importar_csv':
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir el archivo');
            }

            $archivo = $_FILES['archivo']['tmp_name'];
            $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

            if (!in_array($extension, ['csv', 'xlsx'])) {
                throw new Exception('Formato de archivo no válido. Use CSV o Excel');
            }

            $importados = $proveedor->importarProveedores($archivo, $extension);
            echo json_encode([
                'success' => true,
                'message' => "Se importaron $importados proveedores correctamente"
            ]);
            break;

        case 'validar_nif':
            if (empty($_POST['nif'])) {
                throw new Exception('NIF no proporcionado');
            }

            $nif = strtoupper($_POST['nif']);
            $esValido = $proveedor->validarNIF($nif);
            echo json_encode([
                'success' => true,
                'valido' => $esValido
            ]);
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    error_log("Error en proveedor_controller: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}