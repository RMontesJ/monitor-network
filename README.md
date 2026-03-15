🛰️ Network Monitor Pro (Python + PHP + MySQL)
Un sistema de monitoreo de red local automatizado que escanea dispositivos conectados, detecta su estado (UP/DOWN) y visualiza los datos en un panel web moderno en tiempo real.

🚀 Características
Detección Automática de Red: No necesitas configurar tu IP; el script detecta tu rango de red local (ej. 192.168.1.0/24) automáticamente.

Escaneo en Bucle: El script de Python se ejecuta continuamente cada 30 segundos.

Persistencia en Base de Datos: Los dispositivos se guardan en MySQL, evitando duplicados gracias a la gestión de claves únicas por IP.

Interfaz Web Responsiva: Panel visual hecho en PHP y CSS con auto-refresco cada 30 segundos.

Detección de Estado: Identifica visualmente si un dispositivo está activo mediante etiquetas de color.

🛠️ Requisitos del Sistema
1. Software Base
Python 3.x

Servidor Web con PHP (Ejemplo: XAMPP, Laragon, WAMPSERVER)

MySQL / MariaDB

Nmap: El motor de escaneo. Indispensable tenerlo instalado en el SO (Descargar aquí).

2. Librerías de Python
Instala las dependencias necesarias con pip:

Bash
pip install mysql-connector-python python-nmap
📦 Instalación y Configuración
1. Base de Datos
Crea la base de datos y las tablas necesarias ejecutando el siguiente SQL en tu gestor (phpMyAdmin):

SQL
CREATE DATABASE network_monitor;
USE network_monitor;

-- Tabla para los dispositivos
CREATE TABLE scan_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) UNIQUE, 
    hostname VARCHAR(255),
    status VARCHAR(20),
    last_check TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para la info de red
CREATE TABLE network_info (
    id INT PRIMARY KEY,
    current_network VARCHAR(50),
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO network_info (id, current_network) VALUES (1, 'Iniciando...');
2. Configuración de Scripts
Asegúrate de editar las credenciales en monitor.py y index.php:

Usuario: ??

Password: ??

Database: ??

🚦 Ejecución
Inicia el Servidor Web: Asegúrate de que Apache y MySQL estén corriendo.

Lanza el Escáner (Python): * Abre una terminal como Administrador.

Ejecuta: python monitor.py.

Accede al Panel: Abre tu navegador en http://localhost/tu-carpeta/index.php.

📂 Estructura del Proyecto
monitor.py: Script de Python encargado del escaneo y actualización de la BD.

index.php: Interfaz de usuario que consulta y muestra los datos.

style.css: Estilos modernos para el panel web.

README.md: Este archivo informativo.

⚠️ Notas Importantes
Nombres "Desconocidos": Muchos dispositivos (como móviles) bloquean las solicitudes de nombre por privacidad. Es un comportamiento normal de red.

Permisos: El escaneo de red requiere privilegios elevados. Ejecuta siempre el script de Python con permisos de administrador.

Desarrollado con ❤️ para monitoreo de redes locales.
