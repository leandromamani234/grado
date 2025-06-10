<?php
// Iniciar sesión si no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Evitar el caché del navegador para evitar el regreso con botón atrás
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../vista/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="description" content="Sistema de Administración de Agua Potable">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Panel Admin - Sistema de Agua</title>

  <!-- Redes sociales y SEO -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Panel de Administración">
  <meta property="og:title" content="Panel de Administración">
  <meta property="og:url" content="http://sistemadeagua.com">
  <meta property="og:image" content="http://sistemadeagua.com/hero-image.png">
  <meta property="og:description" content="Panel de administración del sistema de agua.">
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:site" content="@sistemadeagua">
  <meta property="twitter:creator" content="@sistemadeagua">

  <!-- Estilos -->
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/responsive.css"> <!-- NUEVO: Responsive -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
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

    .bi-person-circle, .bi-box-arrow-right {
      font-size: 1.2em;
    }
  </style>
</head>

<body class="app sidebar-mini">
  <!-- Navbar superior -->
  <header class="app-header">
    <a class="app-header__logo" href="home.php">
      <img src="images/logo.png" alt="Logo">
      Water System
    </a>

    <!-- Botón para toggle del sidebar -->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Ocultar Sidebar"></a>

    <!-- Menú superior derecho -->
    <ul class="app-nav ml-auto">
      <!-- Usuario -->
      <li class="app-nav-item">
        <span class="app-nav__item">
          <i class="bi bi-person-circle"></i>
          <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        </span>
      </li>

      <!-- Cerrar sesión -->
      <li class="app-nav-item">
        <a class="app-nav__item" href="../controlador/logout.php">
          <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
        </a>
      </li>
    </ul>
  </header>

  <!-- Scripts base -->
  <script src="js/jquery-3.7.0.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
