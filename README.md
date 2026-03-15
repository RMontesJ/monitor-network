SPANISH

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

ENGLISH

Here is a professional and comprehensive README.md file in English for your project.

🛰️ Network Monitor Pro (Python + PHP + MySQL)
An automated local network monitoring system that scans connected devices, detects their status (UP/DOWN), and visualizes the data in a modern web dashboard with real-time updates.

🚀 Features
Automatic Network Detection: No manual IP configuration required; the script automatically detects your local network range (e.g., 192.168.1.0/24).

Continuous Scanning: The Python backend runs in a loop, updating the database every 30 seconds.

Database Persistence: Devices are stored in MySQL, preventing duplicates by using IP addresses as unique keys.

Modern Web Interface: A responsive dashboard built with PHP and CSS featuring a 30-second auto-refresh.

Status Tracking: Visually identify active vs. inactive devices through color-coded status badges.

🛠️ System Requirements
1. Base Software
Python 3.x

Web Server with PHP support (e.g., XAMPP, Laragon, or Nginx/Apache)

MySQL / MariaDB

Nmap: The scanning engine. Mandatory to have it installed on your OS (Download here).

2. Python Libraries
Install the required dependencies using pip:

Bash
pip install mysql-connector-python python-nmap
📦 Installation & Setup
1. Database Setup
Create the database and the necessary tables by executing the following SQL in your manager (like phpMyAdmin):

SQL
CREATE DATABASE network_monitor;
USE network_monitor;

-- Table for devices
CREATE TABLE scan_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) UNIQUE, 
    hostname VARCHAR(255),
    status VARCHAR(20),
    last_check TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for network metadata
CREATE TABLE network_info (
    id INT PRIMARY KEY,
    current_network VARCHAR(50),
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO network_info (id, current_network) VALUES (1, 'Initializing...');
2. Script Configuration
Update the database credentials in both monitor.py and index.php:

Host: ??

User: ??

Password: ??

Database: ??

🚦 How to Run
Start the Web Server: Ensure Apache and MySQL are running.

Launch the Scanner (Python): * Open your terminal as Administrator (Windows) or use sudo (Linux).

Run: python monitor.py.

Access the Dashboard: Open your browser and go to http://localhost/your-folder-name/index.php.

📂 Project Structure
monitor.py: Python script responsible for network scanning and database updates.

index.php: Web interface that queries and displays the data.

style.css: Modern styling for the web dashboard.

README.md: Project documentation (this file).

⚠️ Important Notes
"Unknown" Hostnames: Many devices (like smartphones) block hostname requests for privacy reasons. This is standard network behavior.

Permissions: Network scanning requires elevated privileges. Always run the Python script as an administrator to ensure Nmap can access the network interfaces.

Developed with ❤️ for local network management.
