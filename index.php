<?php
$conn = new mysqli("??", "??", "??", "??");
$net_res = $conn->query("SELECT current_network FROM network_info WHERE id = 1");
$net_data = $net_res->fetch_assoc();
$current_net = $net_data['current_network'] ?? 'Detectando...';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Monitor de Red Pro | AJAX</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .timer-badge { background: #fff3cd; color: #856404; padding: 5px 15px; border-radius: 20px; font-size: 0.9em; font-weight: bold; border: 1px solid #ffeeba; }
        .alias-input { background: #f8f9fa; border: 1px solid #dae1e7; color: #334e68; padding: 4px; border-radius: 4px; width: 140px; }
        .btn-save { cursor: pointer; background: #2ecc71; border: none; color: white; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Monitor de Red Activo</h1>
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                <div class="network-badge">Red: <strong><?php echo htmlspecialchars($current_net); ?></strong></div>
                <div class="timer-badge">Actualizando en: <span id="countdown">60</span>s</div>
            </div>
            <h1>No usar en redes externas sin consentimiento, tiene serias implicaciones legales, si quieres hacer pruebas, hazlas en una red domestica o de confianza</h1>
        </header>

        <section class="table-container" id="tabla-dispositivos">
            <p style="padding:20px; text-align:center;">Cargando dispositivos...</p>
        </section>
    </div>

    <script>
        let seconds = 60;
        const display = document.querySelector('#countdown');

        // Función para cargar la tabla sin recargar la página
        function cargarTabla() {
            fetch('get_table.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('tabla-dispositivos').innerHTML = data;
                });
        }

        // Cuenta atrás y refresco
        setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                seconds = 60; // Reiniciamos contador
                cargarTabla(); // Pedimos datos nuevos
            }
            display.innerText = seconds;
        }, 1000);

        // Cargar la tabla por primera vez al abrir
        cargarTabla();
    </script>
</body>
</html>