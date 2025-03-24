<?php
session_start();
if (isset($_SESSION['usuario'])) {
    echo json_encode(['autenticado' => true]);
} else {
    echo json_encode(['autenticado' => false]);
}

case 'registro':
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $modelo = new Usuario();
    $id = $modelo->crearUsuario($usuario, $contrasena);
    echo json_encode(['mensaje' => 'Usuario registrado correctamente', 'id' => $id]);
    break;

case 'login':
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $modelo = new Usuario();
    $datos = $modelo->verificarCredenciales($usuario, $contrasena);
    if ($datos) {
        session_start();
        $_SESSION['usuario'] = $usuario;
        echo json_encode(['mensaje' => 'Inicio de sesión exitoso']);
    } else {
        echo json_encode(['error' => 'Credenciales incorrectas']);
    }
    break;
    ?>