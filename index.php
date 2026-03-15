<?php
// Configuración de conexión
$conn = new mysqli("localhost", "root", "", "network_monitor");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Ordenar por IP de forma lógica
$query = "SELECT * FROM scan_results ORDER BY INET_ATON(ip_address) ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="30"> <title>Monitor de Red | Panel en Vivo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Monitor de Red Activo</h1>
            <p>Estado de dispositivos detectados automáticamente</p>
            <small>La página se actualiza automáticamente cada 30 segundos</small>
        </header>

        <section class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Dirección IP</th>
                        <th>Nombre del Dispositivo</th>
                        <th>Estado</th>
                        <th>Último Escaneo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $row['ip_address']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['hostname']); ?></td>
                            <td>
                                <span class="status-pill <?php echo $row['status']; ?>">
                                    <?php echo strtoupper($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('H:i:s - d/m/Y', strtotime($row['last_check'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;">Esperando datos del escáner Python...</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>