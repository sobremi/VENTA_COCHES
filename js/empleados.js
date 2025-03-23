document.addEventListener('DOMContentLoaded', function() {
    cargarEmpleados();
    
    document.getElementById('empleadoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarEmpleado();
    });

    // Establecer la fecha actual por defecto
    document.getElementById('fecha_contratacion').valueAsDate = new Date();

    // Búsqueda en tiempo real
    let timeoutId;
    document.getElementById('buscarEmpleado').addEventListener('input', function(e) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => buscarEmpleados(), 300);
    });

    // Filtro por cargo
    document.getElementById('filtroCargo').addEventListener('change', buscarEmpleados);
});

function cargarEmpleados() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/empleado_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const empleados = JSON.parse(xhr.responseText);
                let html = '';
                empleados.forEach(empleado => {
                    html += `
                        <tr>
                            <td>${empleado.id_empleado}</td>
                            <td>${empleado.nombre}</td>
                            <td>${empleado.apellido}</td>
                            <td>${empleado.dni}</td>
                            <td>${empleado.cargo}</td>
                            <td>${empleado.telefono || ''}</td>
                            <td>${empleado.email || ''}</td>
                            <td>${empleado.fecha_contratacion}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning btn-sm" onclick="editarEmpleado(${empleado.id_empleado})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarEmpleado(${empleado.id_empleado})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('tablaEmpleados').innerHTML = html;
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };

    xhr.send('accion=listar');
}

function guardarEmpleado() {
    const formData = new FormData(document.getElementById('empleadoForm'));
    const id = document.getElementById('id_empleado').value;
    
    formData.append('accion', id ? 'actualizar' : 'insertar');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/empleado_controller.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    limpiarFormulario();
                    cargarEmpleados();
                } else {
                    throw new Error(response.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        }
    };

    xhr.send(formData);
}

function editarEmpleado(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/empleado_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const empleado = JSON.parse(xhr.responseText);
            document.getElementById('id_empleado').value = empleado.id_empleado;
            document.getElementById('nombre').value = empleado.nombre;
            document.getElementById('apellido').value = empleado.apellido;
            document.getElementById('dni').value = empleado.dni;
            document.getElementById('cargo').value = empleado.cargo;
            document.getElementById('telefono').value = empleado.telefono || '';
            document.getElementById('email').value = empleado.email || '';
            document.getElementById('fecha_contratacion').value = empleado.fecha_contratacion;
        }
    };

    xhr.send(`accion=obtener&id_empleado=${id}`);
}

function eliminarEmpleado(id) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción no se puede revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controladores/empleado_controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        cargarEmpleados();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            };

            xhr.send(`accion=eliminar&id_empleado=${id}`);
        }
    });
}

function limpiarFormulario() {
    document.getElementById('empleadoForm').reset();
    document.getElementById('id_empleado').value = '';
    document.getElementById('fecha_contratacion').valueAsDate = new Date();
}

function buscarEmpleados() {
    const busqueda = document.getElementById('buscarEmpleado').value.trim();
    const cargo = document.getElementById('filtroCargo').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controladores/empleado_controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const empleados = JSON.parse(xhr.responseText);
                actualizarTablaEmpleados(empleados);
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };

    xhr.send(`accion=buscar&termino=${encodeURIComponent(busqueda)}&cargo=${encodeURIComponent(cargo)}`);
}

function actualizarTablaEmpleados(empleados) {
    let html = '';
    empleados.forEach(empleado => {
        html += `
            <tr>
                <td>${empleado.id_empleado}</td>
                <td>${empleado.nombre}</td>
                <td>${empleado.apellido}</td>
                <td>${empleado.dni}</td>
                <td><span class="badge ${getBadgeClass(empleado.cargo)}">${empleado.cargo}</span></td>
                <td>${empleado.telefono || ''}</td>
                <td>${empleado.email || ''}</td>
                <td>${formatearFecha(empleado.fecha_contratacion)}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-warning btn-sm" onclick="editarEmpleado(${empleado.id_empleado})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarEmpleado(${empleado.id_empleado})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    document.getElementById('tablaEmpleados').innerHTML = html;
}

function getBadgeClass(cargo) {
    const classes = {
        'Gerente': 'bg-primary',
        'Vendedor': 'bg-success',
        'Mecánico': 'bg-warning',
        'Administrativo': 'bg-info'
    };
    return classes[cargo] || 'bg-secondary';
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}