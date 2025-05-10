<?php
// Verificar si la sesión ya ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Evitar el caché del navegador para que no pueda volver con el botón "atrás"
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión, redirigir al login
    header("Location: ../vista/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="description" content="Sistema de Administración">
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:site" content="@sistemadeagua">
  <meta property="twitter:creator" content="@sistemadeagua">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Panel de Administración">
  <meta property="og:title" content="Panel de Administración">
  <meta property="og:url" content="http://sistemadeagua.com">
  <meta property="og:image" content="http://sistemadeagua.com/hero-image.png">
  <meta property="og:description" content="Panel de administración del sistema de agua.">
  <title>Panel Admin - Sistema de Agua</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    /* Estilos de degradado azul inspirado en el cabello */
    .app-header {
      background: linear-gradient(90deg, #0D3B66, #2F80ED, #5DADE2);
      color: white;
    }

    .app-header__logo {
      color: white;
      background-color: #0D3B66;
      display: flex;
      align-items: center;
      padding: 0 15px;
      font-size: 20px;
      font-weight: bold;
    }

    .app-header__logo img {
      width: 35px;
      height: 35px;
      margin-right: 10px;
    }

    .app-nav {
      display: flex;
      align-items: center;
    }

    .app-nav__item {
      color: white;
      font-size: 18px;
      margin-right: 20px;
      text-decoration: none;
    }

    .app-nav__item i {
      margin-right: 5px;
    }

    .app-sidebar__toggle {
      color: white;
      font-size: 20px;
      padding: 15px;
    }

    /* Ajuste de iconos y texto */
    .bi-person-circle, .bi-box-arrow-right {
      font-size: 1.2em;
    }
  </style>
</head>

<body class="app sidebar-mini">
  <!-- Navbar-->
  <header class="app-header">
    <a class="app-header__logo" href="home.php">
      <img src="images/logo.png" alt="Logo"> <!-- Logo del sistema -->
      Water System
    </a>
    
    <!-- Botón para ocultar/mostrar sidebar -->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Ocultar Sidebar"></a>

    <!-- Navbar derecha: Botón de cerrar sesión -->
    <ul class="app-nav ml-auto">
      <!-- Icono del usuario y nombre -->
      <li class="app-nav-item">
        <span class="app-nav__item">
          <i class="bi bi-person-circle"></i> <?php echo $_SESSION['usuario']; ?>
        </span>
      </li>

      <!-- Botón para cerrar sesión -->
      <li class="app-nav-item">
        <a class="app-nav__item" href="../controlador/logout.php">
          <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
        </a>
      </li>
    </ul>
  </header>

  <!-- Script necesarios para funcionalidad del menú -->
  <script src="js/jquery-3.7.0.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
