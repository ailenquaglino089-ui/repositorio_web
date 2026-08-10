<?php
// Calcula la ruta base del proyecto para usarla en enlaces y llamadas API
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// ============================================================
// Consulta a la base de datos
// $pdo viene del closure en routes.php (use ($pdo))
// ============================================================

// Lee los filtros de la URL (query string) con valores por defecto
// $_GET contiene los parámetros de la URL (ej: ?estado=activa&paciente_id=1)
$estado = $_GET['estado'] ?? 'activa';
$paciente_id = $_GET['paciente_id'] ?? null;

// Construcción de la consulta SQL principal
// LEFT JOIN = une las tablas pero mantiene todas las recetas aunque
//             no tengan paciente o médico asociado
$sql = "SELECT p.*, pac.nombre AS paciente_nombre, m.nombre AS medico_nombre
        FROM prescripciones p
        LEFT JOIN pacientes pac ON p.id_paciente = pac.id
        LEFT JOIN medicos m ON p.id_medico = m.id
        WHERE p.estado = ?";

// Si se filtró por paciente, se agrega a la consulta
// (int) castea a entero para prevenir inyección SQL
if ($paciente_id) {
    $sql .= " AND p.id_paciente = " . (int)$paciente_id;
}
$sql .= " ORDER BY p.fecha_emision DESC";

// Prepara y ejecuta la consulta con marcadores (?)
$stmt = $pdo->prepare($sql);
$stmt->execute([$estado]);
$prescripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtiene el mapa de medicamentos [id => nombre] para traducir los
// IDs guardados en formato JSON a texto legible
$medsQuery = $pdo->query("SELECT id, nombre FROM medicamentos");
// FETCH_KEY_PAIR: devuelve un array donde la primer columna es la clave
// y la segunda columna es el valor: [1 => 'Paracetamol', 2 => 'Ibuprofeno']
$mapaMedicamentos = $medsQuery->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Prescripciones - MRx Digital</title>
    <style>
        /* ================================================================
           ESTILOS CSS de la página de prescripciones (recetas médicas)
           ================================================================ */

        /* Fondo degradado y cuerpo de página */
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding-bottom: 40px; }
        .container { max-width: 1200px; margin: auto; padding: 0 12px; }

        /* Título principal con fondo degradado */
        h1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px 28px; border-radius: 16px; box-shadow: 0 12px 30px rgba(102, 126, 234, 0.3); text-align: center; font-size: 2rem; letter-spacing: 0.5px; margin-bottom: 24px; }

        /* Formulario de filtros (por estado y paciente) */
        form { background: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); border-top: 4px solid #667eea; }
        form label { font-weight: 700; color: #1a202c; display: inline-block; margin-right: 16px; font-size: 1rem; }
        form select { padding: 10px 14px; border: 2px solid #667eea; border-radius: 8px; font-size: 0.95rem; cursor: pointer; background: white; color: #1a202c; font-weight: 600; }

        /* Tabla de prescripciones */
        table { width: 100%; border-collapse: collapse; margin-top: 0; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
        th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 12px; text-align: left; font-weight: 700; font-size: 0.95rem; text-transform: uppercase; }
        td { border-bottom: 1px solid #e5e7eb; padding: 14px 12px; text-align: left; }
        tr:hover { background: #f9fafb; }  /* Efecto hover al pasar el mouse */

        /* Estados de las recetas con colores distintivos */
        .estado-activa { background: #dcfce7; color: #15803d; font-weight: 700; padding: 6px 12px; border-radius: 8px; display: inline-block; border: 2px solid #15803d; }
        .estado-dispensada { background: #dbeafe; color: #0c4a6e; font-weight: 700; padding: 6px 12px; border-radius: 8px; display: inline-block; border: 2px solid #0c4a6e; }
        .estado-vencida { background: #fee2e2; color: #7f1d1d; font-weight: 700; padding: 6px 12px; border-radius: 8px; display: inline-block; border: 2px solid #7f1d1d; }
        .estado-cancelada { background: #f3f4f6; color: #4b5563; font-weight: 700; padding: 6px 12px; border-radius: 8px; display: inline-block; border: 2px solid #4b5563; }

        /* Botón de eliminar */
        button { cursor: pointer; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; padding: 10px 14px; border-radius: 8px; font-weight: 700; width: 100%; max-width: 160px; }

        /* Caja informativa legal al pie de página */
        .info-box { margin-top: 28px; padding: 22px 24px; background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); border-left: 6px solid #dc2626; border-radius: 12px; color: white; }

        /* Responsive: en mobile la tabla se convierte en tarjetas */
        @media (max-width: 840px) {
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }  /* Oculta el thead */
            tr { margin-bottom: 18px; border: 2px solid #667eea; border-radius: 12px; background: white; }
            td { border: none; padding: 12px 14px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?php echo $basePath; ?>/notificaciones" style="display:inline-block; text-decoration:none; color:#667eea; font-weight:700; padding:10px 20px; border-radius:10px; background:white; border:2px solid #667eea; margin-bottom:16px;">← HOME</a>
        <h1>📋 MRx Digital - Lista de Recetas Electrónicas</h1>

        <!-- Formulario de filtros: se autoenvía al cambiar el select -->
        <form method="GET">
            <label for="estado">🔍 Filtrar por Estado:</label>
            <select name="estado" id="estado" onchange="this.form.submit()">
                <!-- El operador ternario marca como "selected" la opción actual -->
                <option value="activa" <?php if ($estado == 'activa') echo 'selected'; ?>>✓ Activa</option>
                <option value="dispensada" <?php if ($estado == 'dispensada') echo 'selected'; ?>>✔️ Dispensada</option>
                <option value="vencida" <?php if ($estado == 'vencida') echo 'selected'; ?>>⏰ Vencida</option>
                <option value="cancelada" <?php if ($estado == 'cancelada') echo 'selected'; ?>>✗ Cancelada</option>
            </select>
        </form>

        <!-- Tabla con el listado de prescripciones -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>Medicamentos</th>
                    <th>Indicaciones</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-recetas">
                <?php foreach ($prescripciones as $p): ?>
                <!-- id="fila-X" identifica cada fila para poder eliminarla del DOM -->
                <tr id="fila-<?php echo $p['id']; ?>">
                    <td data-label="ID"><?php echo $p['id']; ?></td>
                    <td data-label="Paciente"><?php echo htmlspecialchars($p['paciente_nombre']); ?></td>
                    <td data-label="Médico"><?php echo htmlspecialchars($p['medico_nombre'] ?: 'No asignado'); ?></td>
                    <td data-label="Fecha Emisión"><?php echo $p['fecha_emision']; ?></td>
                    <td data-label="Fecha Vencimiento"><?php echo $p['fecha_vencimiento']; ?></td>
                    <!-- Los medicamentos se guardan como JSON en la base de datos -->
                    <td data-label="Medicamentos">
                        <?php
                        $items = json_decode($p['medicamentos'], true);  // true = array asociativo
                        if (is_array($items)) {
                            foreach ($items as $item) {
                                $nombreMed = $mapaMedicamentos[$item['id']] ?? "Medicamento desconocido";
                                echo "• <strong>" . htmlspecialchars($nombreMed) . "</strong>: " . htmlspecialchars($item['dosis']) . "<br>";
                            }
                        } else {
                            echo "Sin datos";
                        }
                        ?>
                    </td>
                    <td data-label="Indicaciones"><?php echo htmlspecialchars($p['indicaciones']); ?></td>
                    <!-- Muestra el estado con el color correspondiente -->
                    <td data-label="Estado" class="estado-<?php echo $p['estado']; ?>"><?php echo ucfirst($p['estado']); ?></td>
                    <td data-label="Acciones">
                        <button onclick="eliminarReceta(<?php echo $p['id']; ?>)">🗑️ Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Información legal -->
        <div class="info-box">
            <strong>✓ Información Importante - Receta Validada</strong>
            Esta receta fue creada por un emisor inscripto y validado en el Registro de Recetarios Electrónicos.
        </div>
    </div>

    <!-- ================================================================
    JAVASCRIPT: eliminar recetas vía API
    ================================================================ -->
    <script>
        /**
         * eliminarReceta(id) - Envía DELETE a la API para borrar una receta
         * Si funciona, remueve la fila del DOM sin recargar la página
         * 
         * @param {number} id - ID de la prescripción a eliminar
         */
        async function eliminarReceta(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar permanentemente esta receta?')) return;

            // URL de la API (dinámica según el proyecto)
            const apiUrl = '<?php echo $basePath; ?>/api/prescripciones/' + id;

            try {
                const response = await fetch(apiUrl, { method: 'DELETE' });
                const result = await response.json();

                if (response.ok) {
                    alert(result.message);
                    // document.getElementById() busca un elemento HTML por su ID
                    // .remove() lo elimina del DOM (árbol HTML)
                    document.getElementById('fila-' + id).remove();
                } else {
                    alert('Error: ' + (result.error || 'No se pudo eliminar'));
                }
            } catch (error) {
                // error de red (servidor no disponible, etc.)
                console.error('Error:', error);
                alert('Error de conexión con la API');
            }
        }
    </script>
</body>
</html>
