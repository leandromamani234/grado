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
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(270deg, #6a93ff, #1f4a87, #6a93ff);
    background-size: 600% 600%;
    animation: animFondo 16s ease infinite;
  }

  @keyframes animFondo {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .login-container {
    background-color: rgba(255, 255, 255, 0.1);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    text-align: center;
    width: 360px;
    backdrop-filter: blur(10px);
  }

  .login-container h3 {
    color: white;
    margin-bottom: 25px;
    font-size: 1.9rem;
  }

  .login-container .input-group {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.15);
    border-radius: 30px;
    padding: 10px 15px;
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

  .login-container .input-group i {
    font-size: 1.4rem;
    margin-right: 10px;
    color: white;
  }

  .login-container input {
    border: none;
    outline: none;
    background-color: transparent;
    color: white;
    font-size: 1rem;
    flex: 1;
    padding: 8px;
    border-radius: 30px;
    transition: background-color 0.3s, box-shadow 0.3s;
  }

  .login-container input:focus {
    background-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 0 6px rgba(0, 255, 255, 0.5);
  }

  .login-container input::placeholder {
    color: rgba(255, 255, 255, 0.6);
  }

  .login-container button {
    width: 100%;
    padding: 12px;
    border-radius: 30px;
    background: linear-gradient(to right, #4facfe, #00f2fe);
    color: white;
    font-size: 1.2rem;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: transform 0.3s;
  }

  .login-container button:hover {
    transform: scale(1.03);
  }

  .remember-me {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
    font-size: 0.9rem;
    color: white;
  }

  .remember-me input {
    margin-right: 5px;
  }

  .forgot-password {
    margin-top: 10px;
    text-align: right;
  }

  .forgot-password a {
    color: #d0eaff;
    text-decoration: none;
    font-size: 0.9rem;
  }

  .forgot-password a:hover {
    text-decoration: underline;
  }

  .alert {
    margin-top: 15px;
    background-color: #e63946;
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
