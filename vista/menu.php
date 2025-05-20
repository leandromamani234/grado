<?php
// Redirigir si no está autenticado
if (!isset($_SESSION['id_rol'])) {
    header("Location: ../vista/index.php");
    exit;
}   

include_once "../includes/permisos_menu.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"> <!-- Importa tu archivo CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> <!-- Iconos de Bootstrap -->
    <title>Sistema Dashboard</title>
    <style>
        /* Menú lateral en negro */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #1b1b1b; /* Fondo negro */
            color: white;
            overflow-y: auto;
            transition: width 0.3s ease;
        }

        .app-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .app-menu__item, .treeview-item {
            padding: 15px 20px;
            color: #b0b3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .app-menu__item:hover, .treeview-item:hover {
            background-color: #333333; /* Fondo gris oscuro al pasar el ratón */
            color: #ffffff;
        }

        .app-menu__icon {
            margin-right: 15px;
            font-size: 1.2em;
        }

        .treeview-menu {
            display: none;
            padding-left: 20px;
            background-color: #2c2c2c; /* Fondo de submenús */
            border-left: 1px solid #444; /* Línea a la izquierda */
        }

        .treeview-menu.show {
            display: block;
        }

        .logo-container {
            text-align: center;
            padding: 50px;
        }

        .logo-container img {
            width: 80px;
            border-radius: 50%;
            margin-bottom: 30px;
        }

        .logo-container h3 {
            color: #b0b3b8;
            font-size: 1.2em;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background-color: #121212; /* Fondo de contenido principal en gris oscuro */
            color: #ffffff;
            transition: margin-left 0.3s ease;
        }

        .treeview-indicator {
            margin-left: auto;
            font-size: 0.8em;
        }

        /* Estilo para íconos en los botones */
        .icon {
            margin-right: 10px;
            color: #b0b3b8;
            font-size: 1em;
        }

        /* Responsive para dispositivos móviles */
        @media (max-width: 768px) {
            .app-sidebar {
                width: 60px;
            }

            .app-menu__label {
                display: none;
            }

            .main-content {
                margin-left: 60px;
            }
        }
    </style>
</head>
<body>
  <!-- Menú lateral -->
  <aside class="app-sidebar">
        <!-- Logo -->
        <div class="logo-container">
            <img src="images/LOGO.1.png" alt="Logo">
            <h3>WELCOME</h3>
        </div>

        <ul class="app-menu">
            <!-- Home (siempre visible) -->
            <li>
                <a class="app-menu__item" href="home.php">
                    <i class="app-menu__icon bi bi-house"></i>
                    <span class="app-menu__label">Home</span>
                </a>
            </li>

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['otb'])): ?>
            <!-- OTB 
            */<li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-code-square"></i>
                    <span class="app-menu__label">OTB</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="registroOTB.php"><i class="icon bi bi-dot"></i> Registrar OTB</a></li>
                    <li><a class="treeview-item" href="verOTB.php"><i class="icon bi bi-dot"></i> Ver OTB</a></li>
                </ul>
            </li>/-->
            <?php endif; ?>

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['personas'])): ?>
            <!-- Personas -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-person"></i>
                    <span class="app-menu__label">Personas</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="RegistroPersona.php"><i class="icon bi bi-dot"></i> Registrar Persona</a></li>
                    <li><a class="treeview-item" href="VerPersonas.php"><i class="icon bi bi-dot"></i> Ver Personas</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['socios'])): ?>
            <!-- Socios -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-people"></i>
                    <span class="app-menu__label">Socios</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="RegistroSocio.php"><i class="icon bi bi-dot"></i> Registrar Socio</a></li>
                    <li><a class="treeview-item" href="verSocios.php"><i class="icon bi bi-dot"></i> Ver Socios</a></li>
                </ul>
            </li>
            <?php endif; ?>

           

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['reportes'])): ?>
            <!-- Reportes -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-bar-chart"></i>
                    <span class="app-menu__label">Reportes</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="reporteSocios.php"><i class="icon bi bi-dot"></i> Reporte de Socios</a></li>
                    <li><a class="treeview-item" href="reporteDeudas.php"><i class="icon bi bi-dot"></i> Reporte de Deudas</a></li>
                    <li><a class="treeview-item" href="reporteRecibos.php"><i class="icon bi bi-dot"></i> Reporte de Recibos </a></li>

                </ul>
            </li>
            <?php endif; ?>

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['propiedades'])): ?>
            <!-- Propiedades -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-house-door"></i>
                    <span class="app-menu__label">Propiedades</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="RegistroPropiedades.php"><i class="icon bi bi-dot"></i> Registrar Propiedad</a></li>
                    <li><a class="treeview-item" href="VerPropiedades.php"><i class="icon bi bi-dot"></i> Ver Propiedades</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['medidores'])): ?>
            <!-- Medidores -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-speedometer2"></i>
                    <span class="app-menu__label">Medidores</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="RegistroMedidor.php"><i class="icon bi bi-dot"></i> Registrar Medidor</a></li>
                    <li><a class="treeview-item" href="verMedidores.php"><i class="icon bi bi-dot"></i> Ver Medidores</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['recibos'])): ?>
            <!-- Recibos -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-receipt"></i>
                    <span class="app-menu__label">Recibos</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="registroRecibos.php"><i class="icon bi bi-dot"></i> Registrar Recibo</a></li>
                    <li><a class="treeview-item" href="verRecibos.php"><i class="icon bi bi-dot"></i> Ver Recibos</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['deudas'])): ?>
            <!-- Deudas -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-cash-stack"></i>
                    <span class="app-menu__label">Deudas</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="registroDeudas.php"><i class="icon bi bi-dot"></i> Registrar Deuda</a></li>
                    <li><a class="treeview-item" href="verDeudas.php"><i class="icon bi bi-dot"></i> Ver Deudas</a></li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if (in_array($_SESSION['id_rol'], $permisos_menu['usuarios'])): ?>
            <!-- Usuarios -->
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon bi bi-person-badge"></i>
                    <span class="app-menu__label">Usuarios</span>
                    <i class="treeview-indicator bi bi-chevron-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="RegistroUsuarios.php"><i class="icon bi bi-dot"></i> Registrar Usuario</a></li>
                    <li><a class="treeview-item" href="VerUsuarios.php"><i class="icon bi bi-dot"></i> Ver Usuarios</a></li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </aside>

    <script src="js/jquery-3.7.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.treeview a[data-toggle="treeview"]').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var $treeviewMenu = $this.next('.treeview-menu');

                if ($treeviewMenu.is(':visible')) {
                    $treeviewMenu.slideUp();
                } else {
                    $('.treeview-menu').slideUp();
                    $treeviewMenu.slideDown();
                }
            });
        });
    </script>
</body>
</html>
