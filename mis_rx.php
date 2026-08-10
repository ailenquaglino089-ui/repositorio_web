<?php
// Calcula la ruta base del proyecto para los enlaces
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Rx | SaludWEB</title>
    <style>
        /* ================================================================
           ESTILOS CSS - Dashboard del Paciente (Mis Rx)
           ================================================================ */

        /* variables CSS personalizadas (paleta de colores) */
        :root {
            --primary: #4f46e5;   /* Color primario: Índigo */
            --bg: #f8fafc;        /* Color de fondo */
            --text: #1e293b;      /* Color del texto principal */
            --danger: #e11d48;    /* Color para alertas/acciones peligrosas */
            --success: #10b981;   /* Color para estados activos y correctos */
        }

        /* Estilos generales */
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 20px; margin: 0; }
        .dashboard { max-width: 1200px; margin: 0 auto; }

        /* Encabezado superior con logo y acciones */
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; gap: 15px; flex-wrap: wrap; }
        .logo-container { display: flex; align-items: center; gap: 10px; }
        .logo-box { background: var(--primary); color: white; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 24px; font-weight: bold; }
        .logo-text { font-size: 28px; font-weight: 800; }
        .logo-pro { background: #fee2e2; color: var(--danger); padding: 2px 8px; border-radius: 6px; font-size: 14px; margin-left: 5px; }

        /* Botones del encabezado (Volver, Ajustes, Salir) */
        .header-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .back-link, .config-link, .logout-link { text-decoration: none; font-weight: 800; font-size: 14px; padding: 10px 20px; border-radius: 12px; background: white; border: 2px solid var(--primary); transition: 0.3s; }
        .back-link, .config-link { color: var(--primary); }
        .logout-link { border-color: #ef4444; color: #ef4444; }

        /* Menú de navegación en cuadrícula */
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .menu-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 2px solid #e2e8f0; text-decoration: none; color: var(--text); text-align: center; }

        /* Sección de contacto y soporte */
        .support-section { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); margin-bottom: 24px; }
        .contact-icons { display: flex; gap: 12px; }
        .social-link { display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; border-radius: 50%; text-decoration: none; font-size: 24px; border: 2px solid #e2e8f0; }
        .whatsapp-link { background: #25d366; color: white; }
        .gmail-link { background: #ea4335; color: white; }

        /* Panel de estado del sistema */
        .status-panel { background: #f8fafc; border-radius: 12px; padding: 20px; border: 2px solid #e2e8f0; margin-top: 20px; }
        .status-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
        .status-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 8px; }
        .dot-ok { background: var(--success); }  /* Verde = funcionando */
        .dot-warn { background: #f59e0b; }       /* Amarillo = advertencia */

        /* Botón primario */
        .btn-primary { background: var(--primary); color: white; padding: 12px 24px; border: none; border-radius: 10px; cursor: pointer; font-weight: 800; text-decoration: none; display: inline-block; }

        /* Modal FAQ */
        .modal-overlay { display: none; position: fixed; z-index: 1000; inset: 0; background: rgba(0,0,0,0.4); align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal-box { background: white; border-radius: 16px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.15); max-height: 80vh; overflow-y: auto; }
        .modal-box h2 { margin-top: 0; }
        .faq-item { text-align: left; margin-bottom: 16px; padding: 12px; background: #f8fafc; border-radius: 10px; border: 2px solid #e2e8f0; }
        .faq-item h3 { margin: 0 0 4px; font-size: 15px; color: #1e293b; }
        .faq-item p { margin: 0; font-size: 14px; color: #475569; }

        /* Responsive */
        @media (max-width: 768px) {
            .header-top { flex-direction: column; align-items: flex-start; }
            .grid-menu { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="dashboard">

        <!-- ============================================================
        ENCABEZADO
        ============================================================ -->
        <div class="header-top">
            <div class="logo-container">
                <div class="logo-box">💊</div>
                <div class="logo-text">SaludWEB <span class="logo-pro">PRO</span></div>
            </div>
            <!-- Botones de navegación superior con basePath dinámico -->
            <div class="header-actions">
                <a href="<?php echo $basePath; ?>/notificaciones" class="back-link">← HOME</a>
                <a href="<?php echo $basePath; ?>/configuracion" class="config-link">⚙️ Ajustes</a>
                <a href="<?php echo $basePath; ?>/logout" class="logout-link">🔒 Salir</a>
            </div>
        </div>

        <!-- Subtítulo -->
        <p class="subtitle">Bienvenido a tu centro de control de recetas electrónicas</p>

        <!-- ============================================================
        MENÚ DE NAVEGACIÓN EN CUADRÍCULA
        ============================================================ -->
        <!-- Cuadrícula de tarjetas de navegación principal -->
        <div class="grid-menu">
            <!-- Tarjeta: acceso al listado de recetas electrónicas -->
            <a href="<?php echo $basePath; ?>/prescripciones" class="menu-card">
                <div style="font-size: 36px;">📄</div>
                <strong>Mis Recetas</strong>
                <small style="color: #64748b;">Ver todas</small>
            </a>

            <!-- Tarjeta: acceso al centro de notificaciones y alertas -->
            <a href="<?php echo $basePath; ?>/notificaciones" class="menu-card">
                <div style="font-size: 36px;">🔔</div>
                <strong>Notificaciones</strong>
                <small style="color: #64748b;">Alertas y avisos</small>
            </a>

            <!-- Tarjeta: acceso al listado de prestaciones médicas -->
            <a href="<?php echo $basePath; ?>/prestaciones" class="menu-card">
                <div style="font-size: 36px;">🏥</div>
                <strong>Prestaciones</strong>
                <small style="color: #64748b;">Servicios</small>
            </a>

            <!-- Tarjeta: buscador de farmacias cercanas -->
            <a href="<?php echo $basePath; ?>/buscador-farmacias" class="menu-card">
                <div style="font-size: 36px;">💊</div>
                <strong>Farmacias</strong>
                <small style="color: #64748b;">Buscar cerca</small>
            </a>
        </div>

        <!-- ============================================================
        SOPORTE Y CONTACTO
        ============================================================ -->
        <div class="support-section">
            <h2>📞 Soporte</h2>
            <p>¿Necesitás ayuda? Contactanos por estos canales:</p>
            <div class="contact-icons">
                <a href="https://wa.me/541234567890" target="_blank" class="social-link whatsapp-link" title="WhatsApp">💬</a>
                <a href="mailto:soporte@saludweb.com" class="social-link gmail-link" title="Email">✉️</a>
            </div>
            <p style="margin-top: 16px; color: #64748b;">
                WhatsApp: +54 9 11 1234-5678 | Email: soporte@saludweb.com
            </p>
            <button onclick="abrirFAQ()" class="btn-primary">❓ Preguntas Frecuentes</button>
        </div>

        <!-- Modal FAQ -->
        <div class="modal-overlay" id="modalFAQ">
            <div class="modal-box">
                <h2>❓ Preguntas Frecuentes</h2>
                <div class="faq-item">
                    <h3>¿Cómo creo un nuevo médico?</h3>
                    <p>Andá a la sección Médicos y hacé click en "+ Nuevo Médico". Completá los datos y guardá.</p>
                </div>
                <div class="faq-item">
                    <h3>¿Cómo elimino una receta?</h3>
                    <p>En Prescripciones, hacé click en "Eliminar" sobre la receta que querés borrar.</p>
                </div>
                <div class="faq-item">
                    <h3>¿Qué es Mis Rx?</h3>
                    <p>Es tu panel personal donde podés ver todas tus recetas electrónicas y acceder a las funciones principales del sistema.</p>
                </div>
                <div class="faq-item">
                    <h3>¿Cómo contacto con soporte?</h3>
                    <p>Usá los canales de WhatsApp o Email que aparecen en esta misma sección.</p>
                </div>
                <button class="btn" style="background:#f1f5f9;color:#1e293b;border:2px solid #cbd5e1;margin-top:8px;" onclick="cerrarFAQ()">Cerrar</button>
            </div>
        </div>

        <!-- ============================================================
        ESTADO DEL SISTEMA
        ============================================================ -->
        <div class="support-section">
            <h2>🔧 Estado del Sistema</h2>
            <div class="status-panel">
                <div class="status-item">
                    <span>🔌 Servidor de base de datos</span>
                    <span style="color: var(--success);"><span class="status-dot dot-ok"></span>Operacional</span>
                </div>
                <div class="status-item">
                    <span>📡 API REST</span>
                    <span style="color: var(--success);"><span class="status-dot dot-ok"></span>Operacional</span>
                </div>
                <div class="status-item">
                    <span>☁️ Servicio de recetas electrónicas</span>
                    <span style="color: #f59e0b;"><span class="status-dot dot-warn"></span>Mantenimiento</span>
                </div>
                <div class="status-item">
                    <span>📧 Servicio de notificaciones</span>
                    <span style="color: var(--success);"><span class="status-dot dot-ok"></span>Operacional</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        function abrirFAQ() { document.getElementById('modalFAQ').classList.add('open'); }
        function cerrarFAQ() { document.getElementById('modalFAQ').classList.remove('open'); }
        document.getElementById('modalFAQ').addEventListener('click', function(e) { if (e.target === this) cerrarFAQ(); });
    </script>
</body>
</html>
