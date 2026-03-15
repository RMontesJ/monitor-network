<?php
$conexion = new mysqli("??", "??", "??", "??");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ip = $_POST['ip_address'];
    $nuevo_alias = $_POST['alias'];

    $stmt = $conexion->prepare("UPDATE scan_results SET alias = ? WHERE ip_address = ?");
    $stmt->bind_param("ss", $nuevo_alias, $ip);
    
    if ($stmt->execute()) {
        header("Location: index.php?msg=actualizado");
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }
}
?>