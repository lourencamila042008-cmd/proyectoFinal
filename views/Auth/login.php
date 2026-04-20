<?php
session_start();

if (isset($_SESSION["id_usuario"])) {
    session_unset();
    session_destroy();
    session_start();
}

require_once __DIR__ . "/../../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1️⃣ VALIDAR QUE NO VENGAN VACÍOS
    $usuario = trim($_POST["usuario"] ?? "");
    $clave   = trim($_POST["clave"] ?? "");

    if (empty($usuario) || empty($clave)) {
        $error = "Por favor completa todos los campos.";

    } elseif (strlen($usuario) > 100 || strlen($clave) > 255) {
        // 2️⃣ VALIDAR LONGITUD MÁXIMA
        $error = "Los datos ingresados no son válidos.";

    } else {

        $conn = Database::Conectar();

        $stmt = $conn->prepare("
            SELECT * FROM usuario
            WHERE correo = ? OR nombre_usuario = ?
            LIMIT 1
        ");
        $stmt->bind_param("ss", $usuario, $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            // 3️⃣ VERIFICAR CONTRASEÑA
            if (password_verify($clave, $user["contrasena"])) {

                $_SESSION["id_usuario"] = $user["id_usuario"];
                $_SESSION["usuario"]    = $user["nombre_usuario"];

                $stmtRol = $conn->prepare("
                    SELECT r.tipo
                    FROM rol_user ru
                    INNER JOIN rol r ON ru.id_rol = r.id_rol
                    WHERE ru.id_usuario = ?
                    LIMIT 1
                ");
                $stmtRol->bind_param("i", $user["id_usuario"]);
                $stmtRol->execute();
                $resRol = $stmtRol->get_result();

                if ($resRol->num_rows > 0) {
                    $rol = $resRol->fetch_assoc();
                    $_SESSION["rol"] = $rol["tipo"];

                    if ($rol["tipo"] == "admin") {
                        header("Location: ../Admin/dashboard_admin.php");
                    } else {
                        header("Location: ../Empleado/dashboard_empleado.php");
                    }
                    exit();

                } else {
                    $error = "El usuario no tiene rol asignado. Contacta al administrador.";
                }

            } else {
                // 4️⃣ MENSAJE GENÉRICO — no revela si fue usuario o clave lo incorrecto
                $error = "Credenciales incorrectas. Verifica tus datos.";
            }

        } else {
            $error = "Credenciales incorrectas. Verifica tus datos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="../../public/css/login.css">
</head>

<body>

<div class="login-box">
    <div class="logo">INVOICEPRO</div>
    <h2>Iniciar Sesión</h2>

    <?php if ($error != ""): ?>
        <p style="color:red; text-align:center; font-size:14px; margin-bottom:10px;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input class="input" type="text" name="usuario"
               placeholder="Usuario o correo"
               value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
               maxlength="100" required>

        <input class="input" type="password" name="clave"
               placeholder="Contraseña"
               maxlength="255" required>

        <button type="submit" class="login-btn">Entrar</button>
    </form>

    <a href="register.php" class="link">Registrarse</a>
    <a href="../../index.php" class="link">Volver al inicio</a>
</div>

</body>
</html>