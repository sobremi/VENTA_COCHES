-- Active: 1739176923759@@127.0.0.1@3306@coches
create database consecionario;
use consecionario;

   CREATE TABLE Vehiculos (
    id_vehiculo INT PRIMARY KEY AUTO_INCREMENT,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    año YEAR NOT NULL,
    color VARCHAR(20),
    tipo_combustible ENUM('Gasolina', 'Diésel', 'Eléctrico', 'Híbrido') NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    estado ENUM('Nuevo', 'Usado') NOT NULL,
    kilometraje INT DEFAULT 0,
    fecha_ingreso DATE NOT NULL,
    foto VARCHAR(255), -- Ruta de la imagen o BLOB
    id_proveedor INT,
     disponible TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_proveedor) REFERENCES Proveedores(id_proveedor)
);

DROP TABLE IF EXISTS Proveedores;

CREATE TABLE Proveedores (
    id_proveedor INT PRIMARY KEY AUTO_INCREMENT,
    nombre_empresa VARCHAR(100) NOT NULL,
    nif VARCHAR(20) NOT NULL UNIQUE,
    contacto_nombre VARCHAR(50),
    telefono VARCHAR(15),
    email VARCHAR(100),
    direccion VARCHAR(255),
    estado BOOLEAN DEFAULT true
);

CREATE TABLE Facturas (
    id_factura INT PRIMARY KEY AUTO_INCREMENT,
    id_venta INT NOT NULL,
    numero_factura VARCHAR(50) UNIQUE NOT NULL,
    fecha_emision DATE NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    impuestos DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    metodo_pago ENUM('Efectivo', 'Tarjeta', 'Transferencia') NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES Ventas(id_venta)
);

CREATE TABLE Reportes (
    id_reporte INT PRIMARY KEY AUTO_INCREMENT,
    tipo_reporte ENUM('Ventas', 'Inventario', 'Servicios', 'Reservas') NOT NULL,
    fecha_generacion DATE NOT NULL,
    descripcion TEXT,
    archivo_ruta VARCHAR(255) -- Ruta del archivo PDF o Excel generado
);

CREATE TABLE Garantias (
    id_garantia INT PRIMARY KEY AUTO_INCREMENT,
    id_venta INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    descripcion TEXT,
    estado ENUM('Activa', 'Caducada', 'En proceso') NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES Ventas(id_venta)
);
CREATE TABLE Promociones (
    id_promocion INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    descuento DECIMAL(5, 2) NOT NULL, -- Porcentaje de descuento
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);
CREATE TABLE Vehiculo_Promociones (
    id_vehiculo INT NOT NULL,
    id_promocion INT NOT NULL,
    PRIMARY KEY (id_vehiculo, id_promocion),
    FOREIGN KEY (id_vehiculo) REFERENCES Vehiculos(id_vehiculo),
    FOREIGN KEY (id_promocion) REFERENCES Promociones(id_promocion)
);
CREATE TABLE Comentarios_Clientes (
    id_comentario INT PRIMARY KEY AUTO_INCREMENT,
    id_cliente INT NOT NULL,
    comentario TEXT NOT NULL,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    fecha_comentario DATE NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente)
);

CREATE TABLE Inventario (
    id_inventario INT PRIMARY KEY AUTO_INCREMENT,
    id_vehiculo INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    ubicacion VARCHAR(100),
    FOREIGN KEY (id_vehiculo) REFERENCES Vehiculos(id_vehiculo)
);

CREATE TABLE Ventas (
    id_venta INT PRIMARY KEY AUTO_INCREMENT,
    id_cliente INT NOT NULL,
    id_empleado INT NOT NULL,
    id_vehiculo INT NOT NULL,
    fecha_venta DATE NOT NULL,
    precio_venta DECIMAL(10, 2) NOT NULL,
    forma_pago ENUM('Efectivo', 'Tarjeta', 'Financiamiento') NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente),
    FOREIGN KEY (id_empleado) REFERENCES Empleados(id_empleado),
    FOREIGN KEY (id_vehiculo) REFERENCES Vehiculos(id_vehiculo)
);

CREATE TABLE Empleados (
    id_empleado INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(20) UNIQUE NOT NULL,
    cargo ENUM('Gerente', 'Vendedor', 'Mecánico', 'Administrativo') NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(100),
    fecha_contratacion DATE NOT NULL
);

CREATE TABLE Clientes (
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(100),
    direccion VARCHAR(255),
    imagen VARCHAR(255) -- Ruta de la imagen o BLOB
);
CREATE TABLE Servicios (
    id_servicio INT PRIMARY KEY AUTO_INCREMENT,
    id_vehiculo INT NOT NULL,
    id_empleado INT NOT NULL,
    fecha_servicio DATE NOT NULL,
    descripcion TEXT NOT NULL,
    costo_servicio DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_vehiculo) REFERENCES Vehiculos(id_vehiculo),
    FOREIGN KEY (id_empleado) REFERENCES Empleados(id_empleado)
);

CREATE TABLE ProveedorHistorial (
    id_historial INT PRIMARY KEY AUTO_INCREMENT,
    id_proveedor INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    accion ENUM('crear', 'editar', 'eliminar', 'activar', 'desactivar') NOT NULL,
    usuario VARCHAR(50),
    detalles TEXT,
    FOREIGN KEY (id_proveedor) REFERENCES Proveedores(id_proveedor)
);