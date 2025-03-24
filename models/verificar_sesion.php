<?php
session_start();

error_log("ID de sesión en verificar_sesion.php: " . session_id()); // Registro en el log
error_log("Datos de sesión: " . print_r($_SESSION, true)); // Muestra todas las variables de sesión

if (isset($_SESSION['usuario'])) {
    echo json_encode(['autenticado' => true]);
} else {
    echo json_encode(['autenticado' => false]);
}