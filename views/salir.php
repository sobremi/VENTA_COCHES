<?php
session_start();
if (isset($_SESSION['usuario'])) {
    echo json_encode(['autenticado' => true]);
} else {
    echo json_encode(['autenticado' => false]);
}
// Iniciar la sesión
//session_start();

// Destruir todas las variables de sesión
//session_unset();

// Destruir la sesión
//session_destroy();

// Redirigir al usuario al formulario de inicio de sesión
header('Location: login.php');
exit;
?>
<style>
    .header .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .header .logout-btn:hover {
            background-color: #c82333;
        }
</style>