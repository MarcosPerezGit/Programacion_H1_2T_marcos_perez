DROP DATABASE IF EXISTS streamWeb;
CREATE DATABASE streamWeb;
USE streamWeb;

CREATE TABLE planes_base (
    id_plan INT AUTO_INCREMENT PRIMARY KEY,
    nombre_plan VARCHAR(50) NOT NULL UNIQUE,
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE paquetes (
    id_paquete INT AUTO_INCREMENT PRIMARY KEY,
    nombre_paquete VARCHAR(50) NOT NULL UNIQUE,
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    edad INT NOT NULL CHECK (edad >= 0),
    contraseña VARCHAR(255) NOT NULL,  
    id_plan INT NOT NULL,
    duracion_suscripcion ENUM('Mensual', 'Anual') NOT NULL,
    FOREIGN KEY (id_plan) REFERENCES planes_base(id_plan)
);

CREATE TABLE usuarios_paquetes (
    id_usuario INT,
    id_paquete INT,
    PRIMARY KEY (id_usuario, id_paquete),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_paquete) REFERENCES paquetes(id_paquete) ON DELETE CASCADE
);

INSERT INTO planes_base (nombre_plan, precio) VALUES 
('Básico (9.99€)', 9.99),
('Estándar (13.99€)', 13.99),
('Premium (17.99€)', 17.99);

INSERT INTO paquetes (nombre_paquete, precio) VALUES 
('Deporte (6.99€)', 6.99),
('Cine (7.99€)', 7.99),
('Infantil (4.99€)', 4.99);


SELECT 
    u.id_usuario AS ID,
    u.nombre AS "Nombre de Usuario",
    u.apellidos AS "Apellidos del Usuario",
    u.correo AS "Correo",
    u.edad AS "Edad",
    p.nombre_plan AS "Plan Base",
    GROUP_CONCAT(pa.nombre_paquete SEPARATOR ', ') AS "Paquetes Adicionales",
    u.duracion_suscripcion AS "Duración",
    (p.precio + IFNULL(SUM(pa.precio), 0)) AS "Valor Mensual Total"
FROM usuarios u
JOIN planes_base p ON u.id_plan = p.id_plan
LEFT JOIN usuarios_paquetes up ON u.id_usuario = up.id_usuario
LEFT JOIN paquetes pa ON up.id_paquete = pa.id_paquete
GROUP BY u.id_usuario, p.nombre_plan, p.precio, u.duracion_suscripcion;

select * from paquetes;
select * from usuarios;