<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <!-- Font-icon css-->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <title>Login</title>
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Arial, sans-serif;
      background: linear-gradient(120deg, #6a93ff 0%, #1f4a87 100%);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.15); /* Fondo transparente */
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 350px;
    }

    .login-container h3 {
      color: white;
      margin-bottom: 20px;
      font-size: 1.8rem;
    }

    .login-container .input-group {
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      background-color: rgba(255, 255, 255, 0.2); /* Fondo semi-transparente */
      border-radius: 30px;
      padding: 10px;
      border: 1px solid rgba(255, 255, 255, 0.4); /* Definir un borde claro */
    }

    .login-container .input-group i {
      font-size: 1.5rem;
      margin-right: 10px;
      color: white;
    }

    .login-container input {
      border: none;
      outline: none;
      background: transparent; /* Fondo transparente */
      color: black; /* Texto de color negro */
      font-size: 1rem;
      flex: 1;
      padding-left: 10px; /* Añadir espacio dentro del input */
    }

    .login-container input::placeholder {
      color: rgba(255, 255, 255, 0.6); /* Placeholder blanco translúcido */
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      border-radius: 30px;
      background-color: rgba(76, 141, 255, 0.7); /* Botón más transparente */
      color: white;
      font-size: 1.2rem;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .login-container button:hover {
      background-color: rgba(51, 111, 204, 0.8); /* Botón más oscuro en hover */
    }

    .login-container .forgot-password {
      text-align: right;
      margin-top: 10px;
    }

    .login-container .forgot-password a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
    }

    .login-container .forgot-password a:hover {
      text-decoration: underline;
    }

    .remember-me {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 20px;
      color: white;
    }

    .remember-me label {
      display: flex;
      align-items: center;
    }

    .remember-me input {
      margin-right: 5px;
    }

    .alert {
      margin-top: 15px;
      background-color: #dc3545;
      padding: 10px;
      border-radius: 5px;
      color: white;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h3><i class="bi bi-person me-2"></i>Iniciar Sesión</h3>
    <form class="login-form" action="../controlador/registrologin.php" method="post">
      <div class="input-group">
        <i class="bi bi-person"></i>
        <input class="form-control" type="text" name="usuario" placeholder="Nombre de Usuario" required autofocus>
      </div>

      <div class="input-group">
        <i class="bi bi-lock"></i>
        <input class="form-control" type="password" name="pass" placeholder="Contraseña" required>
      </div>

      <button type="submit">Login</button>

      <div class="remember-me">
        <label><input type="checkbox"> Recordarme</label>
        <div class="forgot-password">
          <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
        </div>
      </div>

      <!-- Mostrar mensajes de error si la autenticación falla -->
      <?php if (isset($_GET['error'])): ?>
        <div class="alert">
          <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
      <?php endif; ?>
    </form>
  </div>

  <!-- Scripts -->
  <script src="js/jquery-3.7.0.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
