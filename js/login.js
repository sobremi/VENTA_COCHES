
// Cambiar entre formularios
function mostrarRegistro() {
    document.getElementById('formularioLogin').style.display = 'none';
    document.getElementById('formularioRegistro').style.display = 'block';
    document.getElementById('tituloFormulario').textContent = 'Registro';
    document.getElementById('cambiarFormulario').innerHTML = '¿Ya tienes cuenta? <a href="#" onclick="mostrarLogin()">Inicia sesión aquí</a>';
}

function mostrarLogin() {
    document.getElementById('formularioRegistro').style.display = 'none';
    document.getElementById('formularioLogin').style.display = 'block';
    document.getElementById('tituloFormulario').textContent = 'Iniciar Sesión';
    document.getElementById('cambiarFormulario').innerHTML = '¿No tienes cuenta? <a href="#" onclick="mostrarRegistro()">Regístrate aquí</a>';
}

// Inicio de sesión
document.getElementById('formularioLogin').addEventListener('submit', function (e) {
    e.preventDefault();
    const usuario = document.getElementById('usuario').value;
    const contrasena = document.getElementById('contrasena').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/login.php?accion=login', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.mensaje) {
                alert(respuesta.mensaje);
                window.location.href = '../views/navegacion.php'; // Redirigir al dashboard
            } else {
                alert(respuesta.error);
            }
        }
    };
    xhr.send(`usuario=${encodeURIComponent(usuario)}&contrasena=${encodeURIComponent(contrasena)}`);
});
 // Verificar si hay una sesión activa
 const xhr = new XMLHttpRequest();
 xhr.open('GET', '../models/verificar_sesion.php', true);
 xhr.onload = function () {
     if (xhr.status === 200) {
         const respuesta = JSON.parse(xhr.responseText);
         if (!respuesta.autenticado) {
             window.location.href = '../views/login.php'; // Redirigir al inicio de sesión
         }
     }
 };
 xhr.send();
// Registro de usuario
document.getElementById('formularioRegistro').addEventListener('submit', function (e) {
    e.preventDefault();
    const nuevoUsuario = document.getElementById('nuevo_usuario').value;
    const nuevaContrasena = document.getElementById('nueva_contrasena').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/login.php?accion=registro', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.mensaje) {
                alert(respuesta.mensaje);
                mostrarLogin(); // Cambiar al formulario de inicio de sesión
            } else {
                alert(respuesta.error);
            }
        }
    };
    xhr.send(`usuario=${encodeURIComponent(nuevoUsuario)}&contrasena=${encodeURIComponent(nuevaContrasena)}`);
});
