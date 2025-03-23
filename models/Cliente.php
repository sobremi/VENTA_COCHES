<?php
$table = 'clientes';

function getAllClientes($conn) {
    global $table;
    $query = "SELECT * FROM " . $table;
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

function createCliente($conn, $nombre, $direccion, $telefono, $email, $dni) {
    global $table;
    $query = "INSERT INTO " . $table . " (nombre, direccion, telefono, email, dni) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$nombre, $direccion, $telefono, $email, $dni]);
}

function updateCliente($conn, $id, $nombre, $direccion, $telefono, $email, $dni) {
    global $table;
    $query = "UPDATE " . $table . " SET nombre=?, direccion=?, telefono=?, email=?, dni=? WHERE id=?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$nombre, $direccion, $telefono, $email, $dni, $id]);
}

function deleteCliente($conn, $id) {
    global $table;
    $query = "DELETE FROM " . $table . " WHERE id=?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$id]);
}
?>
