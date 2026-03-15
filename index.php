<?php
$conn = new mysqli("??", "??", "??", "??");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 1. Obtener la red actual
$net_query = $conn->query("SELECT current_network FROM network_info WHERE id = 1");
$network_data = $net_query->fetch_assoc();
$current_net = $network_data['current_network'] ?? 'Desconocida';

// 2. Obtener los dispositivos
$query = "SELECT * FROM scan_results ORDER BY INET_ATON(ip_address) ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="30">
    <title>Monitor de Red | Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Monitor de Red Activo</h1>
            <div class="network-badge">
                Escaneando Red: <strong><?php echo $current_net; ?></strong>
            </div>
        </header>

        <section class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Dirección IP</th>
                        <th>Dispositivo</th>
                        <th>Estado</th>
                        <th>Último Escaneo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $row['ip_address']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['hostname']); ?></td>
                        <td>
                            <span class="status-pill <?php echo $row['status']; ?>">
                                <?php echo strtoupper($row['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('H:i:s', strtotime($row['last_check'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>