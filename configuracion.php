<?php
// Calcula la ruta base del proyecto para los enlaces
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes | SaludWEB</title>
    <style>
        /* ================================================================
           ESTILOS CSS - Página de configuración / ajustes
           ================================================================ */

        /* Variables de diseño y paleta de colores */
        :root {
            --primary: #4f46e5;   /* Color principal: Índigo */
            --bg: #f8fafc;        /* Color de fondo */
            --text: #1e293b;      /* Color del texto */
            --success: #10b981;   /* Color para estados operacionales */
        }

        /* Estilos generales */
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 20px; margin: 0; }
        .container { max-width: 1000px; margin: 0 auto; }

        /* Encabezado con logotipo y botón de retorno */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
        .logo-container { display: flex; align-items: center; gap: 10px; }
        .logo-box { background: var(--primary); color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 20px; font-weight: bold; }
        .logo-text { font-size: 24px; font-weight: 800; }
        .back-link { text-decoration: none; color: var(--primary); font-weight: 800; padding: 10px 20px; border-radius: 12px; background: white; border: 2px solid var(--primary); }

        /* Menú de navegación con pestañas */
        .nav-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; margin-bottom: 30px; }
        .nav-item { padding: 12px 16px; border-radius: 10px; background: white; text-decoration: none; color: var(--text); font-weight: 700; text-align: center; border: 2px solid #e2e8f0; }
        .nav-item:hover, .nav-item.active { background: var(--primary); color: white; }  /* active = página actual */

        /* Contenido principal con secciones */
        .content { background: white; border-radius: 16px; padding: 28px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .section { margin-bottom: 32px; }
        .section-title { font-size: 20px; font-weight: 800; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px; }
        .btn-close-section { margin-left: auto; background: none; border: none; cursor: pointer; font-size: 18px; color: #94a3b8; padding: 4px 8px; border-radius: 6px; }
        .btn-close-section:hover { background: #fee2e2; color: #ef4444; }
        .section.hidden { display: none; }

        /* Grupos de configuración (filas con toggles) */
        .settings-group { background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 12px; border: 2px solid #e2e8f0; }
        .settings-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; }
        .settings-label { font-weight: 700; }

        /* Botones genéricos */
        .btn { display: inline-block; padding: 12px 20px; border-radius: 10px; border: none; cursor: pointer; font-weight: 800; text-decoration: none; font-size: 14px; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-secondary { background: #f1f5f9; color: #1e293b; border: 2px solid #cbd5e1; }
        .btn-danger { background: #ef4444; color: white; }

        /* Iconos de contacto (WhatsApp / Email) */
        .contact-icons { display: flex; gap: 12px; margin-top: 16px; }
        .social-link { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 24px; border: 2px solid #e2e8f0; }
        .whatsapp-link { background: #25d366; color: white; }
        .gmail-link { background: #ea4335; color: white; }

        /* Caja informativa */
        .info-box { background: #eef2ff; border-left: 4px solid var(--primary); padding: 14px 16px; border-radius: 12px; }

        /* Toast de notificación */
        .toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; font-weight: 700; box-shadow: 0 8px 24px rgba(0,0,0,0.15); display: none; z-index: 9999; animation: slideUp 0.3s ease; }
        .toast.error { background: #ef4444; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Modal */
        .modal-overlay { display: none; position: fixed; z-index: 1000; inset: 0; background: rgba(0,0,0,0.4); align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal-box { background: white; border-radius: 16px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.15); max-height: 80vh; overflow-y: auto; }
        .modal-box h2 { margin-top: 0; }
        .modal-box p { color: #475569; line-height: 1.6; }

        /* Responsive */
        @media (max-width: 768px) {
            .header { flex-direction: column; align-items: flex-start; }
            .nav-menu { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- ============================================================
    ENCABEZADO
    ============================================================ -->
    <div class="header">
        <div class="logo-container">
            <div class="logo-box">⚙️</div>
            <div class="logo-text">Ajustes</div>
        </div>
        <div class="header-actions">
            <a href="<?php echo $basePath; ?>/notificaciones" class="back-link">← HOME</a>
        </div>
    </div>

    <!-- Menú de navegación principal -->
    <div class="nav-menu">
        <a href="<?php echo $basePath; ?>/" class="nav-item">🏠 Home</a>
        <a href="<?php echo $basePath; ?>/prescripciones" class="nav-item">📄 Prescripciones</a>
        <a href="<?php echo $basePath; ?>/prestaciones" class="nav-item">🏥 Prestaciones</a>
        <a href="<?php echo $basePath; ?>/notificaciones" class="nav-item">🔔 Notificaciones</a>
        <a href="<?php echo $basePath; ?>/configuracion" class="nav-item active">⚙️ Ajustes</a>
        <a href="<?php echo $basePath; ?>/cuenta" class="nav-item">👤 Cuenta</a>
        <a href="<?php echo $basePath; ?>/faq" class="nav-item">❓ FAQ</a>
        <a href="<?php echo $basePath; ?>/logout" class="nav-item" style="border-color:#ef4444; color:#ef4444;">🔒 Salir</a>
    </div>

    <!-- Toast de notificación -->
    <div class="toast" id="toast"></div>

    <!-- Modal para FAQ / Documentación -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <h2 id="modalTitle"></h2>
            <div id="modalBody"></div>
            <button class="btn btn-secondary" onclick="cerrarModal()" style="margin-top:16px;">Cerrar</button>
        </div>
    </div>

    <!-- ============================================================
    CONTENIDO PRINCIPAL: secciones de configuración
    ============================================================ -->
    <div class="content">

        <!-- Sección: Notificaciones -->
        <div class="section" data-seccion="notificaciones">
            <h2 class="section-title">🔔 Notificaciones
                <button class="btn-close-section" onclick="eliminarSeccion('notificaciones')" title="Eliminar sección">✕</button>
            </h2>
            <div class="settings-group">
                <div class="settings-row">
                    <span class="settings-label">Avisos de prescripciones</span>
                    <input type="checkbox" id="chkPrescripciones" onchange="guardarPreferencia('avisos_prescripciones', this.checked)">
                </div>
            </div>
            <div class="settings-group">
                <div class="settings-row">
                    <span class="settings-label">Recordatorios de medicamentos</span>
                    <input type="checkbox" id="chkRecordatorios" onchange="guardarPreferencia('recordatorios', this.checked)">
                </div>
            </div>
        </div>

        <!-- Sección: Privacidad y Seguridad -->
        <div class="section" data-seccion="privacidad">
            <h2 class="section-title">🔒 Privacidad y Seguridad
                <button class="btn-close-section" onclick="eliminarSeccion('privacidad')" title="Eliminar sección">✕</button>
            </h2>
            <div class="settings-group">
                <div class="settings-row">
                    <span class="settings-label">Autenticación de dos factores</span>
                    <span class="settings-value" id="estado2fa">Desactivado</span>
                </div>
            </div>
            <button class="btn btn-primary" id="btn2fa" onclick="toggle2FA()">🔐 Activar 2FA</button>
        </div>

        <!-- Sección: Contacto y Soporte -->
        <div class="section" data-seccion="contacto">
            <h2 class="section-title">📞 Contacto y Soporte
                <button class="btn-close-section" onclick="eliminarSeccion('contacto')" title="Eliminar sección">✕</button>
            </h2>
            <div class="contact-icons">
                <a href="https://wa.me/541234567890" target="_blank" class="social-link whatsapp-link" title="WhatsApp">💬</a>
                <a href="mailto:soporte@saludweb.com" class="social-link gmail-link" title="Email">✉️</a>
            </div>
            <p style="color: #64748b;">WhatsApp: +54 9 11 1234-5678 | Email: soporte@saludweb.com</p>
        </div>

        <!-- Sección: Centro de Ayuda -->
        <div class="section" data-seccion="ayuda">
            <h2 class="section-title">❓ Centro de Ayuda
                <button class="btn-close-section" onclick="eliminarSeccion('ayuda')" title="Eliminar sección">✕</button>
            </h2>
            <button class="btn btn-secondary" onclick="abrirFAQ()">📖 Ver Preguntas Frecuentes</button>
            <button class="btn btn-secondary" onclick="abrirDocumentacion()">📚 Ver Documentación</button>
        </div>

        <!-- Sección: Administración de datos -->
        <div class="section" data-seccion="datos">
            <h2 class="section-title">📊 Mis Datos
                <button class="btn-close-section" onclick="eliminarSeccion('datos')" title="Eliminar sección">✕</button>
            </h2>
            <button class="btn btn-danger" onclick="eliminarCuenta()">🗑️ Eliminar Cuenta</button>
        </div>

        <!-- Botón para restaurar secciones eliminadas -->
        <div id="restaurarSecciones" style="display:none; text-align:center; margin-top:16px;">
            <button class="btn btn-secondary" onclick="restaurarSecciones()">↩️ Restaurar secciones eliminadas</button>
        </div>

    </div>

</div>

<script>
    // ============================================================
    // Cargar preferencias guardadas al iniciar
    // ============================================================
    function cargarPreferencias() {
        const prefs = JSON.parse(localStorage.getItem('saludweb_prefs') || '{}');
        document.getElementById('chkPrescripciones').checked = prefs.avisos_prescripciones !== false;
        document.getElementById('chkRecordatorios').checked = prefs.recordatorios !== false;

        if (prefs.dosfa_activado) {
            document.getElementById('estado2fa').textContent = 'Activado';
            document.getElementById('btn2fa').textContent = '🔐 Desactivar 2FA';
            document.getElementById('btn2fa').className = 'btn btn-danger';
        }

        // Ocultar secciones eliminadas
        const seccionesEliminadas = prefs.secciones_eliminadas || [];
        seccionesEliminadas.forEach(function(id) {
            var el = document.querySelector('[data-seccion="' + id + '"]');
            if (el) el.classList.add('hidden');
        });
        if (seccionesEliminadas.length > 0) {
            document.getElementById('restaurarSecciones').style.display = 'block';
        }
    }

    // ============================================================
    // Eliminar sección
    // ============================================================
    function eliminarSeccion(id) {
        if (!confirm('¿Eliminar esta sección? Podés restaurarla después.')) return;
        var el = document.querySelector('[data-seccion="' + id + '"]');
        if (el) el.classList.add('hidden');
        const prefs = JSON.parse(localStorage.getItem('saludweb_prefs') || '{}');
        prefs.secciones_eliminadas = prefs.secciones_eliminadas || [];
        if (prefs.secciones_eliminadas.indexOf(id) === -1) {
            prefs.secciones_eliminadas.push(id);
        }
        localStorage.setItem('saludweb_prefs', JSON.stringify(prefs));
        document.getElementById('restaurarSecciones').style.display = 'block';
        mostrarToast('Sección eliminada');
    }

    // ============================================================
    // Restaurar secciones
    // ============================================================
    function restaurarSecciones() {
        if (!confirm('¿Restaurar todas las secciones eliminadas?')) return;
        const prefs = JSON.parse(localStorage.getItem('saludweb_prefs') || '{}');
        prefs.secciones_eliminadas = [];
        localStorage.setItem('saludweb_prefs', JSON.stringify(prefs));
        document.querySelectorAll('.section.hidden').forEach(function(el) {
            el.classList.remove('hidden');
        });
        document.getElementById('restaurarSecciones').style.display = 'none';
        mostrarToast('Secciones restauradas');
    }

    // ============================================================
    // Guardar preferencia en localStorage
    // ============================================================
    function guardarPreferencia(clave, valor) {
        const prefs = JSON.parse(localStorage.getItem('saludweb_prefs') || '{}');
        prefs[clave] = valor;
        localStorage.setItem('saludweb_prefs', JSON.stringify(prefs));
        mostrarToast('Preferencia guardada');
    }

    // ============================================================
    // Toggle 2FA
    // ============================================================
    function toggle2FA() {
        const prefs = JSON.parse(localStorage.getItem('saludweb_prefs') || '{}');
        prefs.dosfa_activado = !prefs.dosfa_activado;
        localStorage.setItem('saludweb_prefs', JSON.stringify(prefs));

        const estado = document.getElementById('estado2fa');
        const btn = document.getElementById('btn2fa');
        if (prefs.dosfa_activado) {
            estado.textContent = 'Activado';
            estado.style.color = '#10b981';
            btn.textContent = '🔐 Desactivar 2FA';
            btn.className = 'btn btn-danger';
            mostrarToast('2FA activado correctamente');
        } else {
            estado.textContent = 'Desactivado';
            estado.style.color = '';
            btn.textContent = '🔐 Activar 2FA';
            btn.className = 'btn btn-primary';
            mostrarToast('2FA desactivado');
        }
    }

    // ============================================================
    // Modal: FAQ
    // ============================================================
    function abrirFAQ() {
        document.getElementById('modalTitle').textContent = '❓ Preguntas Frecuentes';
        document.getElementById('modalBody').innerHTML = `
            <p><strong>¿Cómo crear un médico?</strong><br>Andá a Médicos y hacé click en "+ Nuevo Médico".</p>
            <p><strong>¿Cómo eliminar una receta?</strong><br>En Prescripciones, hacé click en "Eliminar" sobre la receta que querés borrar.</p>
            <p><strong>¿Para qué sirve Mis Rx?</strong><br>Es tu panel personal de recetas electrónicas.</p>
            <p><strong>¿Cómo contacto con soporte?</strong><br>Usá los canales de WhatsApp o Email en la sección Contacto.</p>
        `;
        document.getElementById('modalOverlay').classList.add('open');
    }

    // ============================================================
    // Modal: Documentación
    // ============================================================
    function abrirDocumentacion() {
        document.getElementById('modalTitle').textContent = '📚 Documentación del Sistema';
        document.getElementById('modalBody').innerHTML = `
            <p><strong>Arquitectura:</strong> MVC (Modelo-Vista-Controlador) con capas de persistencia y servicio.</p>
            <p><strong>Base de datos:</strong> MySQL con tablas: médicos, pacientes, prescripciones, medicamentos, obras_sociales, triages, auditoria.</p>
            <p><strong>API REST:</strong> Endpoints para CRUD de médicos y prescripciones.</p>
            <p><strong>Frontend:</strong> HTML + CSS + JavaScript vanilla con fetch a la API.</p>
            <p><strong>Ruteo:</strong> Front Controller vía .htaccess + Router PHP.</p>
        `;
        document.getElementById('modalOverlay').classList.add('open');
    }

    // ============================================================
    // Cerrar modal
    // ============================================================
    function cerrarModal() {
        document.getElementById('modalOverlay').classList.remove('open');
    }

    // ============================================================
    // Eliminar cuenta
    // ============================================================
    function eliminarCuenta() {
        if (!confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) return;
        if (!confirm('¿Estás realmente seguro? Se eliminarán todos tus datos.')) return;
        localStorage.removeItem('saludweb_prefs');
        mostrarToast('Cuenta eliminada. Redirigiendo...', true);
        setTimeout(function() {
            window.location.href = '<?php echo $basePath; ?>/logout';
        }, 1500);
    }

    // ============================================================
    // Toast
    // ============================================================
    function mostrarToast(mensaje, esError) {
        var toast = document.getElementById('toast');
        toast.textContent = mensaje;
        toast.className = 'toast' + (esError ? ' error' : '');
        toast.style.display = 'block';
        setTimeout(function() { toast.style.display = 'none'; }, 2500);
    }

    // Cerrar modal al hacer click fuera
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    // Inicializar
    cargarPreferencias();
</script>
</body>
</html>
