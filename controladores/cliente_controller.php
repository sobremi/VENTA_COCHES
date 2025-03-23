<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log para debugging
file_put_contents('debug.log', print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents('debug.log', print_r($_FILES, true) . "\n", FILE_APPEND);

require_once '../config/conexion.php';

header('Content-Type: application/json');

try {
    $conexion = getConexion();
    
    if (!isset($_POST['accion'])) {
        throw new Exception('Acción no especificada');
    }

    switch($_POST['accion']) {
        case 'insertar':
            // Validar campos requeridos
            if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['dni'])) {
                throw new Exception('Los campos nombre, apellido y DNI son obligatorios');
            }

            // Manejar la subida de la imagen
            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension;
                $rutaCompleta = $uploadDir . $nombreArchivo;
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
                    $imagen = $nombreArchivo;
                }
            }

            $stmt = $conexion->prepare("INSERT INTO clientes (nombre, apellido, dni, telefono, email, direccion, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $resultado = $stmt->execute([
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['dni'],
                $_POST['telefono'],
                $_POST['email'],
                $_POST['direccion'],
                $imagen
            ]);

            echo json_encode([
                'success' => $resultado,
                'message' => 'Cliente guardado correctamente'
            ]);
            break;

        case 'listar':
            $stmt = $conexion->query("SELECT * FROM clientes ORDER BY id_cliente DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'obtener':
            if (!isset($_POST['id_cliente'])) {
                throw new Exception('ID de cliente no especificado');
            }

            $stmt = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
            $stmt->execute([$_POST['id_cliente']]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$cliente) {
                throw new Exception('Cliente no encontrado');
            }

            echo json_encode($cliente);
            break;

        case 'actualizar':
            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension;
                $rutaCompleta = $uploadDir . $nombreArchivo;
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
                    $imagen = $nombreArchivo;
                    
                    // Eliminar imagen anterior
                    $stmt = $conexion->prepare("SELECT imagen FROM clientes WHERE id_cliente = ?");
                    $stmt->execute([$_POST['id_cliente']]);
                    $imagenAnterior = $stmt->fetchColumn();
                    
                    if ($imagenAnterior && file_exists($uploadDir . $imagenAnterior)) {
                        unlink($uploadDir . $imagenAnterior);
                    }
                }
            }

            $sql = "UPDATE clientes SET nombre=?, apellido=?, dni=?, telefono=?, email=?, direccion=?";
            $params = [
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['dni'],
                $_POST['telefono'],
                $_POST['email'],
                $_POST['direccion']
            ];

            if ($imagen) {
                $sql .= ", imagen=?";
                $params[] = $imagen;
            }

            $sql .= " WHERE id_cliente=?";
            $params[] = $_POST['id_cliente'];

            $stmt = $conexion->prepare($sql);
            $resultado = $stmt->execute($params);

            echo json_encode([
                'success' => $resultado,
                'message' => 'Cliente actualizado correctamente'
            ]);
            break;

        case 'eliminar':
            if (!isset($_POST['id_cliente'])) {
                throw new Exception('ID de cliente no especificado');
            }

            // Eliminar imagen si existe
            $stmt = $conexion->prepare("SELECT imagen FROM clientes WHERE id_cliente = ?");
            $stmt->execute([$_POST['id_cliente']]);
            $imagen = $stmt->fetchColumn();
            
            if ($imagen && file_exists("../uploads/$imagen")) {
                unlink("../uploads/$imagen");
            }

            $stmt = $conexion->prepare("DELETE FROM clientes WHERE id_cliente = ?");
            $resultado = $stmt->execute([$_POST['id_cliente']]);
            
            echo json_encode([
                'success' => $resultado,
                'message' => 'Cliente eliminado correctamente'
            ]);
            break;

        case 'buscar':
            if (!isset($_POST['termino'])) {
                throw new Exception('Término de búsqueda no especificado');
            }

            $termino = '%' . $_POST['termino'] . '%';
            $sql = "SELECT * FROM clientes WHERE 
                    nombre LIKE ? OR 
                    apellido LIKE ? OR 
                    dni LIKE ? OR 
                    telefono LIKE ? OR 
                    email LIKE ? OR
                    direccion LIKE ?
                    ORDER BY id_cliente DESC";
                    
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                $termino, $termino, $termino, 
                $termino, $termino, $termino
            ]);
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