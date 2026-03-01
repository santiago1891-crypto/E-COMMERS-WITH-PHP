-- Crear base de datos
CREATE DATABASE tienda;
USE tienda;

-- Tabla base Usuario
CREATE TABLE Usuario (
    ID_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    n_puerta VARCHAR(10),
    calle VARCHAR(100),
    ciudad VARCHAR(100),
    n_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);

-- Subtipo: Usuario Administrativo
CREATE TABLE Usuario_Administrativo (
    ID_usuario INT PRIMARY KEY,
    permisos VARCHAR(255),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
);

-- Subtipo: Usuario Común
CREATE TABLE Usuario_Comun (
    ID_usuario INT PRIMARY KEY,
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
);

-- Tabla Carrito
CREATE TABLE Carrito (
    ID_carrito INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    total DECIMAL(10,2) DEFAULT 0
);

-- Tabla Productos
CREATE TABLE Productos (
    ID_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0
);

-- Relación N:N Carrito - Productos con atributo cantidad
CREATE TABLE Carrito_Productos (
    ID_carrito INT,
    ID_producto INT,
    cantidad INT NOT NULL,
    PRIMARY KEY (ID_carrito, ID_producto),
    FOREIGN KEY (ID_carrito) REFERENCES Carrito(ID_carrito),
    FOREIGN KEY (ID_producto) REFERENCES Productos(ID_producto)
);



-- Tabla Compra
CREATE TABLE Compra (
    ID_compra INT AUTO_INCREMENT PRIMARY KEY,
    ID_usuario INT,
    metodo_pago VARCHAR(50),
    fecha_compra DATE,
    precio_total DECIMAL(10,2),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
);

-- Tabla Factura (relación 1:1 con Compra)
CREATE TABLE Factura (
    ID_factura INT AUTO_INCREMENT PRIMARY KEY,
    ID_compra INT UNIQUE,
    factura_total DECIMAL(10,2),
    fecha_factura DATE,
    FOREIGN KEY (ID_compra) REFERENCES Compra(ID_compra)
);





-- Relación Compra - Carrito (1:N)
ALTER TABLE Compra
ADD COLUMN ID_carrito INT,
ADD FOREIGN KEY (ID_carrito) REFERENCES Carrito(ID_carrito);

--Tabla tarjeta_temporal

CREATE TABLE TarjetaTemporal (
    ID_tarjeta INT AUTO_INCREMENT PRIMARY KEY,
    ID_usuario INT,
    numero VARCHAR(30),
    vencimiento VARCHAR(10),
    cvv VARCHAR(5),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
);







-- Relación Compra - Carrito (1:N)
-- Relación Compra - Carrito (1:N)
-- Relación Compra - Carrito (1:N)





-- Registros

INSERT INTO Productos (nombre_producto, precio_unitario, stock)
VALUES
('vino rosado reserva', 850, 20),
('vino blanco premium', 750, 20),
('vino rosado', 700, 20),
('vino espumoso brut', 850, 20),
('vino dulce postre', 750, 20),
('vino crianza', 700, 20);

