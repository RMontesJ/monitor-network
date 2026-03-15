import nmap
import mysql.connector
import socket
import time

# --- CONFIGURACIÓN ---
DB_CONFIG = {
    'host': '??',
    'user': '??',       
    'password': '??',       
    'database': '??'
}
INTERVALO_SEGUNDOS = 60 

def get_network_range():
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.connect(("8.8.8.8", 80))
        ip_local = s.getsockname()[0]
        s.close()
        return ".".join(ip_local.split('.')[:-1]) + ".0/24"
    except Exception:
        return "192.168.1.0/24"

def update_database(devices, network):
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor()
        
        cursor.execute("UPDATE network_info SET current_network = %s WHERE id = 1", (network,))
        
        # Marcamos como 'down' pero respetamos los nombres ya existentes
        cursor.execute("UPDATE scan_results SET status = 'down'")
        
        # SQL MEJORADO: 
        # 1. Actualiza estado y fecha.
        # 2. Solo actualiza el hostname si el nuevo NO es "Desconocido" (así no perdemos info vieja).
        sql_devices = """
            INSERT INTO scan_results (ip_address, hostname, status) 
            VALUES (%s, %s, %s)
            ON DUPLICATE KEY UPDATE 
                status = VALUES(status), 
                hostname = IF(VALUES(hostname) != 'Desconocido', VALUES(hostname), hostname),
                last_check = CURRENT_TIMESTAMP
        """
        data = [(d['ip'], d['name'], d['status']) for d in devices]
        cursor.executemany(sql_devices, data)
        
        conn.commit()
        print(f"[*] BD Sincronizada. Red: {network} | Activos: {len(devices)}")
    except mysql.connector.Error as err:
        print(f"[!] Error de MySQL: {err}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            conn.close()

def main():
    nm = nmap.PortScanner()
    print("=== MONITOR DE RED MEJORADO ===")
    
    while True:
        target_network = get_network_range()
        print(f"\n[+] ESCANEANDO: {target_network}")
        
        try:
            # Escaneo con resolución de nombres mejorada
            nm.scan(hosts=target_network, arguments='-sP --system-dns')
            
            found_devices = []
            for host in nm.all_hosts():
                nombre = nm[host].hostname()
                if not nombre:
                    try:
                        nombre = socket.gethostbyaddr(host)[0]
                    except:
                        nombre = "Desconocido"

                found_devices.append({
                    'ip': host,
                    'name': nombre,
                    'status': nm[host].state()
                })
            
            update_database(found_devices, target_network)
            
        except Exception as e:
            print(f"[!] Error durante el escaneo: {e}")
        
        # --- CUENTA ATRÁS EN TIEMPO REAL ---
        print("") # Espacio estético
        for i in range(INTERVALO_SEGUNDOS, 0, -1):
            # \r hace que el cursor vuelva al inicio de la línea
            # end="" evita que salte a una línea nueva
            print(f"[*] Siguiente escaneo en: {i} segundos...   ", end="\r")
            time.sleep(1)
        
        print("[*] Iniciando escaneo ahora...                      ", end="\r")

if __name__ == "__main__":
    main()