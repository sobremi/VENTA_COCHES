document.addEventListener('DOMContentLoaded', function() {
    cargarClientes();
    
    // Manejar el envío del formulario
    document.getElementById('clienteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarCliente();
    });

    // Búsqueda en tiempo real
    let timeoutId;
    document.getElementById('buscarCliente').addEventListener('input', function(e) {
        clearTimeout(timeoutId); // Limpiar el timeout anterior
        
        // Esperar 300ms después de que el usuario deje de escribir
        timeoutId = setTimeout(() => {
            const busqueda = e.target.value.trim();
            
            // Si el campo está vacío, mostrar todos los clientes
            if (busqueda === '') {
                cargarClientes();
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controladores/cliente_controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const clientes = JSON.parse(xhr.responseText);
                            let html = '';
                            clientes.forEach(cliente => {
                                html += `
                                    <tr>
                                        <td>${cliente.id_cliente}</td>
                                        <td>${cliente.nombre}</td>
                                        <td>${cliente.apellido}</td>
                                        <td>${cliente.dni}</td>
                                        <td>${cliente.telefono}</td>
                                        <td>${cliente.email}</td>
                                        <td>${cliente.direccion}</td>
                                        <td>${cliente.imagen ? 
                                            `<img src="../uploads/${cliente.imagen}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                            'Sin imagen'}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-warning btn-sm" onclick="editarCliente(${cliente.id_cliente})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="eliminarCliente(${cliente.id_cliente})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            });
                            document.getElementById('tablaClientes').innerHTML = html;
                        } catch (error) {
                            console.error('Error al procesar la respuesta:', error);
                        }
                    }
                }
            };

            xhr.send('accion=buscar&termino=' + encodeURIComponent(busqueda));
        }, 300); // Esperar 300ms antes de hacer la búsqueda
    });

    // Preview de imagen
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagenPreview').innerHTML = `
                    <img src="${e.target.result}" class="img-preview rounded">
                `;
            }
            reader.readAsDataURL(file);
        }
    });
});

function cargarClientes() {
    $.ajax({
        url: '../controladores/cliente_controller.php',
        type: 'POST',
        data: {accion: 'listar'},
        success: function(response) {
            try {
                const clientes = JSON.parse(response);
                let html = '';
                
                clientes.forEach(cliente => {
                    html += `
                        <tr>
                            <td>${cliente.id_cliente}</td>
                            <td>${cliente.nombre}</td>
                            <td>${cliente.apellido}</td>
                            <td>${cliente.dni}</td>
                            <td>${cliente.telefono}</td>
                            <td>${cliente.email}</td>
                            <td>${cliente.direccion}</td>
                            <td>${cliente.imagen ? 
                                `<img src="../uploads/${cliente.imagen}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                'Sin imagen'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editarCliente(${cliente.id_cliente})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarCliente(${cliente.id_cliente})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                $('#tablaClientes').html(html);
            } catch(e) {
                console.error('Error:', e);
            }
        }
    });
}

function eliminarCliente(id) {
    if(confirm('¿Está seguro de eliminar este cliente?')) {
        $.ajax({
            url: '/controllers/cliente_controller.php',
            type: 'POST',
            data: {action: 'delete', id: id},
            success: function() {
                alert('Cliente eliminado');
                cargarClientes();
            }
        });
    }
}

function guardarCliente() {
    const formData = new FormData(document.getElementById('clienteForm'));
    const id = document.getElementById('id_cliente').value;
    
    // Agregar la acción
    formData.append('accion', id ? 'actualizar' : 'insertar');

    // Debug para ver los datos
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    $.ajax({
        url: '../controladores/cliente_controller.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            try {
                const res = JSON.parse(response);
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: id ? 'Cliente actualizado correctamente' : 'Cliente guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    limpiarFormulario();
                    cargarClientes();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message || 'Error al procesar la operación'
                    });
                }
            } catch(e) {
                console.error('Error al procesar respuesta:', e);
                console.log('Respuesta cruda:', response);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la respuesta del servidor'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en la comunicación con el servidor'
            });
        }
    });
}

function limpiarFormulario() {
    document.getElementById('clienteForm').reset();
    document.getElementById('id_cliente').value = '';
    document.getElementById('imagenPreview').innerHTML = '';
}
