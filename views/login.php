
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Concesionario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .switch-form {
            text-align: center;
            margin-top: 16px;
        }
        .switch-form a {
            color: #007bff;
            text-decoration: none;
        }
        .switch-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include "./navegacion.php"?>
    <div class="container">
        <h1 id="tituloFormulario">Iniciar Sesión</h1>
        <form id="formularioLogin">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <form id="formularioRegistro" style="display: none;">
            <label for="nuevo_usuario">Nuevo Usuario:</label>
            <input type="text" id="nuevo_usuario" name="nuevo_usuario" required>
            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
            <button type="submit">Registrar</button>
        </form>
        <div class="switch-form">
            <span id="cambiarFormulario">¿No tienes cuenta? <a href="#" onclick="mostrarRegistro()">Regístrate aquí</a></span>
        </div>
    </div>

   <script src="../js/login.js"></script>
</body>
</html>