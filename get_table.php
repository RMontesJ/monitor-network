<?php
$conn = new mysqli("??", "??", "??", "??");
$query = "SELECT * FROM scan_results ORDER BY INET_ATON(ip_address) ASC";
$result = $conn->query($query);
?>
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
            <tr><td colspan="4" style="text-align:center;">No hay dispositivos detectados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>