<?php
// Calcula la ruta base del proyecto para usarla en los enlaces
// Ej: "/Organizacion_Modulos"
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Médicos - SaludWEB</title>
    <style>
        /* ================================================================
           ESTILOS CSS de la página de gestión de médicos
           ================================================================ */

        /* Estilos generales del body: fuente, fondo y padding */
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; padding: 30px; }

        /* .card = tarjeta contenedora principal */
        .card { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); max-width: 1000px; margin: auto; }

        /* Título principal */
        h1 { color: #1e293b; margin-top: 0; display: flex; align-items: center; gap: 10px; }

        /* Estilos de la tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; color: #64748b; font-size: 0.75rem; padding: 12px; border-bottom: 2px solid #f1f5f9; text-transform: uppercase; }
        td { padding: 14px 12px; border-bottom: 1px solid #f1f5f9; }

        /* .badge = etiqueta de estado (OPERATIVO / INACTIVO) */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
        .active { background: #d1fae5; color: #065f46; }   /* Verde = activo */
        .inactive { background: #fee2e2; color: #991b1b; } /* Rojo = inactivo */

        /* Botones genéricos de acción */
        .btn { border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.8rem; }
        .btn-edit { background: #e0e7ff; color: #3730a3; }     /* Botón editar */
        .btn-edit:hover { background: #c7d2fe; }
        .btn-delete { background: #fee2e2; color: #991b1b; }   /* Botón eliminar */
        .btn-delete:hover { background: #fecaca; }

        /* Botón "volver" */
        .btn-back { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #6366f1; font-weight: 600; }

        /* Botón principal (índigo) */
        .btn-primary { background: #4f46e5; color: white; padding: 10px 20px; border: none; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 14px; }
        .btn-primary:hover { background: #3730a3; }

        /* Contenedor del encabezado con título y botón "Nuevo Médico" */
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }

        /* ================================================================
           MODAL (ventana emergente para alta/edición)
           ================================================================ */
        .modal { display: none; position: fixed; z-index: 1000; inset: 0; background: rgba(0,0,0,0.4); align-items: center; justify-content: center; }
        .modal.open { display: flex; }  /* Cuando se agrega la clase "open", se muestra */
        .modal-content { background: white; border-radius: 16px; padding: 28px; max-width: 480px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .modal-content h2 { margin-top: 0; color: #1e293b; }
        .modal-content label { display: block; font-weight: 600; margin: 12px 0 4px; color: #334155; }
        .modal-content input { width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box; }
        .modal-content input:focus { border-color: #4f46e5; outline: none; }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        .btn-cancel { background: #f1f5f9; color: #475569; padding: 10px 20px; border: none; border-radius: 10px; cursor: pointer; font-weight: 700; }

        /* ================================================================
           RESPONSIVE (adaptación a pantallas chicas)
           ================================================================ */
        @media (max-width: 760px) {
            body { padding: 16px; }
            .card { padding: 20px; }
            table { display: block; width: 100%; overflow-x: auto; }
            th, td { white-space: nowrap; }
            .btn { width: 100%; margin-top: 4px; }
            td { padding: 12px 8px; }
            .header-actions { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Enlace para volver al dashboard (home) -->
        <a href="<?php echo $basePath; ?>/notificaciones" class="btn-back">← HOME</a>

        <!-- Encabezado con título y botón de alta -->
        <div class="header-actions">
            <h1>👨‍⚕️ Panel de Profesionales</h1>
            <!-- onclick="abrirModalAlta()" -> abre el modal en modo "crear" -->
            <button class="btn-primary" onclick="abrirModalAlta()">+ Nuevo Médico</button>
        </div>
        <p>Administra los médicos habilitados para emitir recetas electrónicas.</p>

        <!-- Tabla de médicos -->
        <table>
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Nombre del Profesional</th>
                    <th>Matrícula</th>
                    <th>Especialidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicos as $m): ?>
                <!-- id="medico-X" identifica la fila de cada médico -->
                <tr id="medico-<?php echo $m['id']; ?>">
                    <td>
                        <!-- Badge de estado: clase 'active' (verde) o 'inactive' (rojo) -->
                        <span class="badge <?php echo $m['activo'] ? 'active' : 'inactive'; ?>">
                            <?php echo $m['activo'] ? 'OPERATIVO' : 'INACTIVO'; ?>
                        </span>
                    </td>
                    <!-- htmlspecialchars() escapa caracteres HTML para prevenir XSS -->
                    <td><strong><?php echo htmlspecialchars($m['nombre']); ?></strong></td>
                    <!-- Operador ?: si matricula está vacío, muestra 'N/A' -->
                    <td><code><?php echo htmlspecialchars($m['matricula'] ?: 'N/A'); ?></code></td>
                    <td><?php echo htmlspecialchars($m['especialidad'] ?: 'General'); ?></td>
                    <td>
                        <!-- Botón para activar/desactivar (toggle) -->
                        <button class="btn btn-toggle"
                                style="background: <?php echo $m['activo'] ? '#fee2e2' : '#d1fae5'; ?>; color: <?php echo $m['activo'] ? '#991b1b' : '#065f46'; ?>"
                                onclick="toggleMedico(<?php echo $m['id']; ?>, <?php echo $m['activo'] ? 0 : 1; ?>)">
                            <?php echo $m['activo'] ? 'Desactivar' : 'Activar'; ?>
                        </button>
                        <!-- Botón editar: abre el modal con datos precargados -->
                        <button class="btn btn-edit" onclick="abrirModalEditar(<?php echo $m['id']; ?>)">Editar</button>
                        <!-- Botón eliminar: pide confirmación y borra -->
                        <button class="btn btn-delete" onclick="eliminarMedico(<?php echo $m['id']; ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ================================================================
    MODAL: formulario para crear o editar un médico
    ================================================================ -->
    <div class="modal" id="modalMedico">
        <div class="modal-content">
            <h2 id="modalTitle">Nuevo Médico</h2>
            <!-- onsubmit="guardarMedico(event)" -> captura el envío del formulario -->
            <form id="formMedico" onsubmit="guardarMedico(event)">
                <!-- Input oculto: si tiene valor, estamos editando; si no, creando -->
                <input type="hidden" id="medicoId">

                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" required>

                <label for="matricula">Matrícula</label>
                <input type="text" id="matricula">

                <label for="especialidad">Especialidad</label>
                <input type="text" id="especialidad">

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================================================================
    JAVASCRIPT: funciones para interactuar con la API REST
    ================================================================ -->
    <script>
        // API = ruta base de la API REST de médicos
        // <?php echo $basePath; ?> se reemplaza por PHP en el servidor
        // Ej: "/Organizacion_Modulos/api/medicos"
        const API = '<?php echo $basePath; ?>/api/medicos';

        /**
         * getBaseUrl() - Devuelve la ruta base del proyecto
         * Útil para los enlaces en la navegación
         */
        function getBaseUrl() {
            return '<?php echo $basePath; ?>';
        }

        /**
         * toggleMedico(id, nuevoEstado) - Activa o desactiva un médico
         * Envía una petición PATCH a la API con el nuevo estado
         * 
         * @param {number} id - ID del médico
         * @param {number} nuevoEstado - 1 para activar, 0 para desactivar
         * 
         * fetch() = función nativa de JavaScript para hacer peticiones HTTP
         * async/await = sintaxis para trabajar con promesas (código asíncrono)
         *               async declara la función como asíncrona
         *               await espera a que la promesa se resuelva
         * try/catch = bloque para capturar errores
         */
        async function toggleMedico(id, nuevoEstado) {
            try {
                const response = await fetch(API + '/' + id, {
                    method: 'PATCH',  // PATCH = modificación parcial
                    headers: { 'Content-Type': 'application/json' },  // Envía JSON
                    body: JSON.stringify({ activo: nuevoEstado })  // Convierte objeto a JSON
                });
                if (response.ok) {
                    // response.ok = true si el status HTTP es 200-299
                    location.reload();  // Recarga la página para ver los cambios
                } else {
                    alert('Error al actualizar el estado del médico.');
                }
            } catch (error) {
                // catch = atrapa errores de red (servidor caído, etc.)
                console.error('Error:', error);  // console.error = muestra en consola del navegador
                alert('Error de conexión con la API.');
            }
        }

        /**
         * abrirModalAlta() - Abre el modal para crear un nuevo médico
         * Limpia el formulario y cambia el título
         */
        function abrirModalAlta() {
            document.getElementById('modalTitle').textContent = 'Nuevo Médico';
            document.getElementById('medicoId').value = '';  // Vacío = modo creación
            document.getElementById('formMedico').reset();   // Limpia todos los campos
            document.getElementById('modalMedico').classList.add('open');  // Muestra el modal
        }

        /**
         * abrirModalEditar(id) - Abre el modal para editar un médico
         * Primero obtiene los datos del médico desde la API (GET)
         * Luego llena el formulario con esos datos
         * 
         * @param {number} id - ID del médico a editar
         */
        async function abrirModalEditar(id) {
            try {
                // fetch() sin método = GET (obtener datos)
                const response = await fetch(API + '/' + id);
                if (!response.ok) {
                    alert('Error al obtener datos del médico.');
                    return;
                }
                const medico = await response.json();  // Convierte la respuesta JSON a objeto
                document.getElementById('modalTitle').textContent = 'Editar Médico';
                document.getElementById('medicoId').value = medico.id;  // Guarda el ID
                document.getElementById('nombre').value = medico.nombre;
                document.getElementById('matricula').value = medico.matricula || '';
                document.getElementById('especialidad').value = medico.especialidad || '';
                document.getElementById('modalMedico').classList.add('open');
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión con la API.');
            }
        }

        /**
         * cerrarModal() - Cierra el modal
         */
        function cerrarModal() {
            document.getElementById('modalMedico').classList.remove('open');
        }

        /**
         * guardarMedico(event) - Guarda (crea o actualiza) un médico
         * Prevenimos el comportamiento por defecto del formulario
         * Si hay ID -> PUT (actualizar), si no -> POST (crear)
         * 
         * @param {Event} event - Evento de envío del formulario
         * 
         * event.preventDefault() = evita que el formulario recargue la página
         */
        async function guardarMedico(event) {
            event.preventDefault();
            const id = document.getElementById('medicoId').value;
            // Recolecta los datos del formulario
            const data = {
                nombre: document.getElementById('nombre').value.trim(),
                matricula: document.getElementById('matricula').value.trim(),
                especialidad: document.getElementById('especialidad').value.trim()
            };

            try {
                // Si hay id, es una actualización (PUT), si no, es creación (POST)
                const method = id ? 'PUT' : 'POST';
                const url = id ? API + '/' + id : API;
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    location.reload();  // Recarga para mostrar cambios
                } else {
                    const err = await response.json();
                    alert('Error: ' + (err.error || 'No se pudo guardar.'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión con la API.');
            }
        }

        /**
         * eliminarMedico(id) - Elimina un médico
         * Pide confirmación antes de borrar
         * 
         * @param {number} id - ID del médico
         * 
         * confirm() = muestra un diálogo con Aceptar/Cancelar
         */
        async function eliminarMedico(id) {
            if (!confirm('¿Estás seguro de eliminar este médico?')) return;
            try {
                const response = await fetch(API + '/' + id, {
                    method: 'DELETE'
                });
                const text = await response.text();
                let data;
                try { data = JSON.parse(text); } catch (e) { data = { error: text }; }
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'No se pudo eliminar.'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión con la API. Revisá la consola (F12).');
            }
        }

        // Cuando se hace click en el fondo del modal (no en el contenido), lo cierra
        document.getElementById('modalMedico').addEventListener('click', function (e) {
            // e.target = elemento clickeado, this = modal
            if (e.target === this) cerrarModal();
        });
    </script>
</body>
</html>
