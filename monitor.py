import nmap
import mysql.connector
import socket
import time

# --- CONFIGURACIÓN (Ajusta tus credenciales aquí) ---
DB_CONFIG = {
    'host': '??',
    'user': '??',       
    'password': '??',       
    'database': '??'
}
INTERVALO_SEGUNDOS = 30 

def get_network_range():
    """Detecta la IP local y devuelve el rango /24 automáticamente"""
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
        
        # 1. Actualizar la red actual en la tabla network_info
        cursor.execute("UPDATE network_info SET current_network = %s WHERE id = 1", (network,))
        
        # 2. LIMPIEZA DE ESTADO: Ponemos todos en 'down' antes de marcar los activos
        # Esto permite que los que ya no responden cambien de estado en la web
        cursor.execute("UPDATE scan_results SET status = 'down'")
        
        # 3. Insertar o actualizar los dispositivos encontrados
        sql_devices = """
            INSERT INTO scan_results (ip_address, hostname, status) 
            VALUES (%s, %s, %s)
            ON DUPLICATE KEY UPDATE 
                status = VALUES(status), 
                hostname = VALUES(hostname),
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
    print("=== MONITOR DE RED INICIADO (PRESIONA CTRL+C PARA SALIR) ===")
    
    while True:
        target_network = get_network_range()
        print(f"\n[+] ESCANEANDO: {target_network}")
        
        try:
            # -sn: Ping scan | -PR: ARP request (más efectivo para encontrar routers)
            # --system-dns: Intenta resolver nombres usando el SO
            nm.scan(hosts=target_network, arguments='-sn -PR --system-dns')
            
            found_devices = []
            for host in nm.all_hosts():
                found_devices.append({
                    'ip': host,
                    'name': nm[host].hostname() or "Desconocido",
                    'status': nm[host].state() # Devuelve 'up'
                })
            
            update_database(found_devices, target_network)
            
        except Exception as e:
            print(f"[!] Error durante el escaneo: {e}")
        
        print(f"[*] Esperando {INTERVALO_SEGUNDOS} segundos para el próximo ciclo...")
        time.sleep(INTERVALO_SEGUNDOS)

if __name__ == "__main__":
    main()