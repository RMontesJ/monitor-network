<?php
// Configuración de conexión
$conn = new mysqli("localhost", "Rafa", "1234", "network_monitor");

if ($conn->connect_error) {
    die("<div style='color:red;'>Error de conexión: " . $conn->connect_error . "</div>");
}

$net_res = $conn->query("SELECT current_network FROM network_info WHERE id = 1");
$net_data = $net_res->fetch_assoc();
$current_net = $net_data['current_network'] ?? 'Detectando...';

$query = "SELECT * FROM scan_results ORDER BY INET_ATON(ip_address) ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="30"> <title>Monitor de Red | Panel en Vivo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .alias-input {
            background: rgba(255,255,255,0.1);
            border: 1px solid #444;
            color: #334e68;
            padding: 4px;
            border-radius: 4px;
            width: 140px;
        }
        .btn-save {
            cursor: pointer;
            background: #2ecc71;
            border: none;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Monitor de Red Activo</h1>
            <div class="network-badge">
                Escaneando Red: <strong><?php echo htmlspecialchars($current_net); ?></strong>
            </div>
        </header>

        <section class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Dirección IP</th>
                        <th>Identificación (Hostname / Alias)</th>
                        <th>Estado</th>
                        <th>Última Actividad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $row['ip_address']; ?></strong></td>
                            <td>
                                <form action="guardar_alias.php" method="POST" style="display:flex; gap:5px; align-items:center;">
                                    <input type="hidden" name="ip_address" value="<?php echo $row['ip_address']; ?>">
                                    <input type="text" name="alias" class="alias-input" 
                                           placeholder="<?php echo ($row['hostname'] != 'Desconocido') ? htmlspecialchars($row['hostname']) : 'Pon un nombre...'; ?>" 
                                           value="<?php echo htmlspecialchars($row['alias'] ?? ''); ?>">
                                    <button type="submit" class="btn-save">✓</button>
                                </form>
                            </td>
                            <td>
                                <span class="status-pill <?php echo $row['status']; ?>">
                                    <?php echo strtoupper($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('H:i:s - d/m/Y', strtotime($row['last_check'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;">Cargando datos del escáner...</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>