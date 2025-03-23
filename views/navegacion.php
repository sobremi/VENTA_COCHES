<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Navegación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #1a237e;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
        }
        .navbar-custom .nav-link:hover {
            color: #bbdefb;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #1a237e;
            padding-top: 20px;
            color: white;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-menu {
            padding: 0;
            list-style: none;
        }

        .sidebar-menu li {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-menu .nav-link {
            color: white;
            text-decoration: none;
            font-size: 1.1em;
        }

        .sidebar-menu .nav-link:hover {
            color: #bbdefb;
        }

        .sidebar-menu i {
            width: 25px;
            margin-right: 10px;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .content-wrapper {
                margin-left: 0;
            }
        }

        .toggle-btn {
            position: fixed;
            left: 10px;
            top: 10px;
            z-index: 999;
            display: none;
        }

        @media (max-width: 768px) {
            .toggle-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <button class="btn btn-primary toggle-btn" type="button">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-car me-2"></i>Concesionario</h3>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
            </li>
            <li>
                <a class="nav-link" href="vehiculos.php">
                    <i class="fas fa-car-side"></i>Vehículos
                </a>
            </li>
            <li>
                <a class="nav-link" href="clientes.php">
                    <i class="fas fa-users"></i>Clientes
                </a>
            </li>
            <li>
                <a class="nav-link" href="ventas.php">
                    <i class="fas fa-shopping-cart"></i>Ventas
                </a>
            </li>
            <li>
                <a class="nav-link" href="./empleados.php">
                    <i class="fas fa-user"></i>Empleados
                </a>
            </li>
            <li>
                <a class="nav-link" href="proveedores.php">
                    <i class="fas fa-truck"></i>Proveedores
                </a>
            </li>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>