<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

$id_usuario = $_SESSION['id'];

$query = "SELECT titulo, mensaje, leida, fecha_creacion, id_empresa 
          FROM notificaciones 
          WHERE id_usuario = $id_usuario 
          ORDER BY fecha_creacion DESC";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        body {
            background: linear-gradient(120deg, #f4f7fa 60%, #c2e9fb 100%);
        }
        .notificaciones-container {
            max-width: 820px;
            margin: 48px auto;
            padding: 38px 36px 24px 36px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(44,62,80,0.16);
            border: 1.5px solid #e3e6ed;
        }
        .carta-notificacion {
            border: 1.5px solid #e8eaf6;
            background: linear-gradient(120deg, #f8fafc 80%, #e3f2fd 100%);
            border-radius: 16px;
            margin-bottom: 32px;
            box-shadow: 0 6px 24px rgba(44,62,80,0.06);
            padding: 32px 36px 22px 54px;
            position: relative;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .carta-notificacion:hover {
            box-shadow: 0 14px 36px rgba(44,62,80,0.14);
            transform: translateY(-2px) scale(1.01);
        }
        .carta-notificacion.nueva {
            
            background: linear-gradient(120deg, #e3fdfd 70%, #ffffff 100%);
        }
        .carta-notificacion.leida {
            border-left: 10px solid #b0bec5;
            background: linear-gradient(120deg, #f3f5f7 80%, #ece9f6 100%);
        }
        .icono-carta {
            position: absolute;
            left: -38px;
            top: 24px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 4px 18px rgba(44,62,80,0.15);
            padding: 13px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .titulo-carta {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #26334d;
            letter-spacing: 0.01em;
        }
        .mensaje-carta {
            font-size: 1.08rem;
            color: #495057;
            margin-bottom: 0.5rem;
            border-left: 4px solid #e3e6ed;
            padding-left: 13px;
            background: #f7fafc;
            border-radius: 0 8px 8px 0;
        }
        .footer-carta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.99rem;
            margin-top: 16px;
            color: #7b8490;
        }
        .estado-nueva {
            color: #009688;
            font-weight: 600;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
        }
        .estado-leida {
            color: #8e99a6;
            display: flex;
            align-items: center;
        }
        .btn-gestion {
            margin-left: 10px;
            margin-top: 10px;
            font-size: 0.97rem;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
        }
        .btn-gestion ion-icon {
            margin-right: 3px;
        }
        .no-notificaciones {
            text-align: center;
            margin-top: 80px;
        }
        .notificaciones-header {
            border-bottom: 2.5px solid #0dcaf0;
            margin-bottom: 36px;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, #f4f7fa 80%, #c2e9fb 100%);
            border-radius: 18px 18px 0 0;
        }
        .notificaciones-header ion-icon {
            font-size: 2em;
            color: #0d6efd;
        }
        .notificaciones-header h2 {
            font-weight: 700;
            margin: 0;
            color: #2d3a4b;
            font-size: 1.55rem;
            letter-spacing: 0.02em;
        }
        @media (max-width: 600px) {
            .notificaciones-container {
                padding: 12px 2px 8px 2px;
            }
            .carta-notificacion {
                padding: 16px 10px 15px 36px;
            }
            .icono-carta {
                left: -18px;
                top: 15px;
                padding: 8px 9px;
            }
            .notificaciones-header {
                flex-direction: column;
                gap: 5px;
                padding-bottom: 8px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="notificaciones-container">
        <div class="notificaciones-header">
            <ion-icon name="mail-unread-outline"></ion-icon>
            <h2>Mis Notificaciones</h2>
        </div>
        <?php
        if (mysqli_num_rows($resultado) > 0) {
            while ($notificacion = mysqli_fetch_assoc($resultado)) {
                $leida = $notificacion['leida'];
                $estado = $leida ?
                    "<span class='estado-leida'><ion-icon name='checkmark-done-outline' style='vertical-align:middle; color:#8e99a6; font-size:1.1em;'></ion-icon>&nbsp;Leída</span>" :
                    "<span class='estado-nueva'><ion-icon name='mail-open-outline' style='vertical-align:middle; color:#0dcaf0; font-size:1.1em;'></ion-icon>&nbsp;Nueva</span>";
                $cardClass = $leida ? "leida" : "nueva";
                $icono = $leida ? "notifications-outline" : "notifications-sharp";
                $iconoColor = $leida ? "#b0bec5" : "#0dcaf0";
                echo "<div class='carta-notificacion $cardClass'>
                        <div class='icono-carta'>
                            <ion-icon name='$icono' style='font-size:2em; color:$iconoColor;'></ion-icon>
                        </div>
                        <div class='titulo-carta'>{$notificacion['titulo']}</div>
                        <div class='mensaje-carta'>{$notificacion['mensaje']}</div>
                        <div class='footer-carta'>
                            <span>
                                <ion-icon name='calendar-outline' style='vertical-align:middle;'></ion-icon>
                                <small class='ms-1'>{$notificacion['fecha_creacion']}</small>
                            </span>
                            <span>$estado</span>
                        </div>";
                if ($notificacion['id_empresa']) {
                    echo "<a href='mis_empresas.php?id_empresa={$notificacion['id_empresa']}' class='btn btn-outline-primary btn-sm btn-gestion'>
                        <ion-icon name='business-outline'></ion-icon> Ver Empresa
                    </a>";
                }
                echo "</div>";
            }
        } else {
            echo "<div class='alert alert-warning no-notificaciones' role='alert'>
                <ion-icon name='notifications-off-circle-outline' style='font-size:2em; color:#ffc107; vertical-align:middle;'></ion-icon>
                &nbsp;No tienes notificaciones en este momento.
            </div>";
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>