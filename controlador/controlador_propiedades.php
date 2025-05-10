<?php
require_once '../modelo/modelo_registroPropiedades.php';
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$propiedadModel = new ModeloPropiedad();

if ($action === 'registrar') {
    if (isset($_POST['manzano'], $_POST['numero'], $_POST['id_persona'])) {
        $manzano = htmlspecialchars(trim($_POST['manzano']));
        $numero = (int)$_POST['numero'];
        $id_socio = (int)$_POST['id_persona'];

        $propiedadModel->asignar('manzano', $manzano);
        $propiedadModel->asignar('numero', $numero);
        $propiedadModel->asignar('id_socio', $id_socio);

        $resultado = $propiedadModel->validar();

        if ($resultado === true) {
            $_SESSION['mensaje'] = "Propiedad registrada con éxito.";
            $_SESSION['tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = $resultado;
            $_SESSION['tipo'] = "danger";
        }

        header("Location: ../vista/verPropiedades.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: Datos incompletos.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/verPropiedades.php");
        exit();
    }
}

if ($action === 'editar') {
    if (isset($_POST['id_propiedad'], $_POST['manzano'], $_POST['numero'], $_POST['id_persona'])) {
        $id_propiedad = (int)$_POST['id_propiedad'];
        $manzano = htmlspecialchars(trim($_POST['manzano']));
        $numero = (int)$_POST['numero'];
        $id_socio = (int)$_POST['id_persona'];

        // Conexión directa para validaciones simples
        require_once '../modelo/conexion/conexionBase.php';
        $conexion = new ConexionBase();
        if (!$conexion->CreateConnection()) {
            $_SESSION['mensaje'] = "Error de conexión con la base de datos.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/editarPropiedad.php?id_propiedad=$id_propiedad");
            exit();
        }

        $conn = $conexion->getConnection();

        // 1. Verificar que el nuevo número no esté usado por otra propiedad
        $sql_num = "SELECT id_propiedades FROM propiedades WHERE numero = ? AND id_propiedades != ?";
        $stmt = $conn->prepare($sql_num);
        $stmt->bind_param('ii', $numero, $id_propiedad);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['mensaje'] = "Este número ya está registrado en otra propiedad.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/editarPropiedad.php?id_propiedad=$id_propiedad");
            exit();
        }
        $stmt->close();

        // 2. Verificar que el socio no tenga otra propiedad
        $sql_socio = "SELECT id_propiedades FROM propiedades WHERE id_socio = ? AND id_propiedades != ?";
        $stmt2 = $conn->prepare($sql_socio);
        $stmt2->bind_param('ii', $id_socio, $id_propiedad);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        if ($res2->num_rows > 0) {
            $_SESSION['mensaje'] = "Este socio ya tiene una propiedad.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/editarPropiedad.php?id_propiedad=$id_propiedad");
            exit();
        }
        $stmt2->close();
        $conexion->CloseConnection();

        // 3. Si todo está bien, actualizamos
        $resultado = $propiedadModel->actualizarPropiedad($id_propiedad, $manzano, $numero, $id_socio);

        if ($resultado === true) {
            $_SESSION['mensaje'] = "Propiedad actualizada correctamente.";
            $_SESSION['tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = $resultado;
            $_SESSION['tipo'] = "danger";
        }

        header("Location: ../vista/verPropiedades.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: Datos incompletos para editar.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/verPropiedades.php");
        exit();
    }
}

if ($action === 'eliminar') {
    if (isset($_GET['id_propiedad'])) {
        $id_propiedad = (int)$_GET['id_propiedad'];
        $resultado = $propiedadModel->eliminar($id_propiedad);

        if ($resultado === true) {
            $_SESSION['mensaje'] = "Propiedad eliminada con éxito.";
            $_SESSION['tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = $resultado;
            $_SESSION['tipo'] = "danger";
        }

        header("Location: ../vista/verPropiedades.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: No se proporcionó ID válido.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/verPropiedades.php");
        exit();
    }
}
