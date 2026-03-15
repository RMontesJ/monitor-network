import nmap
import mysql.connector
import socket
import time

# --- CONFIGURACIÓN ---
DB_CONFIG = {
    'host': 'localhost',
    'user': 'Rafa',       
    'password': '1234',       
    'database': 'network_monitor'
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

def update_database(devices):
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor()
        
        sql = """
            INSERT INTO scan_results (ip_address, hostname, status) 
            VALUES (%s, %s, %s)
            ON DUPLICATE KEY UPDATE 
                status = VALUES(status), 
                hostname = VALUES(hostname),
                last_check = CURRENT_TIMESTAMP
        """
        
        data = [(d['ip'], d['name'], d['status']) for d in devices]
        cursor.executemany(sql, data)
        conn.commit()
        print(f"[*] Base de datos actualizada con {len(devices)} dispositivos.")
    except mysql.connector.Error as err:
        print(f"[!] Error de MySQL: {err}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            conn.close()

def main():
    target_network = get_network_range()
    nm = nmap.PortScanner()
    
    while True:
        print(f"\n[*] Escaneando red: {target_network}")
        try:
            nm.scan(hosts=target_network, arguments='-sn')
            found_devices = []
            for host in nm.all_hosts():
                found_devices.append({
                    'ip': host,
                    'name': nm[host].hostname() or "Desconocido",
                    'status': nm[host].state()
                })
            update_database(found_devices)
        except Exception as e:
            print(f"[!] Error durante el escaneo: {e}")
        
        print(f"[*] Esperando {INTERVALO_SEGUNDOS} segundos...")
        time.sleep(INTERVALO_SEGUNDOS)

if __name__ == "__main__":
    main()