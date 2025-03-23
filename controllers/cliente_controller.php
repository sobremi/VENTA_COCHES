<?php
require_once '../config/Database.php';
require_once '../models/Cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $cliente->getAll();
    $output = '';
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output .= "<tr>";
        $output .= "<td>{$row['id']}</td>";
        $output .= "<td>{$row['nombre']}</td>";
        $output .= "<td>{$row['direccion']}</td>";
        $output .= "<td>{$row['telefono']}</td>";
        $output .= "<td>{$row['email']}</td>";
        $output .= "<td>
            <button onclick='editarCliente({$row['id']})'>Editar</button>
            <button onclick='eliminarCliente({$row['id']})'>Eliminar</button>
        </td>";
        $output .= "</tr>";
    }
    echo $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        echo $cliente->delete($_POST['id']);
    } else {
        if (isset($_POST['id'])) {
            echo $cliente->update(
                $_POST['id'],
                $_POST['nombre'],
                $_POST['direccion'],
                $_POST['telefono'],
                $_POST['email']
            );
        } else {
            echo $cliente->create(
                $_POST['nombre'],
                $_POST['direccion'],
                $_POST['telefono'],
                $_POST['email']
            );
        }
    }
}
?>
