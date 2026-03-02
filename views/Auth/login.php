<?php
session_start();

// 🔥 LIMPIAR SESIÓN SI VIENE DEL INDEX
if (isset($_SESSION["id_usuario"])) {
    session_unset();
    session_destroy();
    session_start();
}

require_once __DIR__ . "/../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST["usuario"];
    $clave   = $_POST["clave"];

    $conn = Database::Conectar();

    // 🔐 BUSCAR USUARIO
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

        // 🔑 VERIFICAR CONTRASEÑA ENCRIPTADA
        if (password_verify($clave, $user["contraseña"])) {

            $_SESSION["id_usuario"] = $user["id_usuario"];
            $_SESSION["usuario"]    = $user["nombre_usuario"];

            // 🔎 OBTENER ROL
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

                // 🚀 REDIRECCIÓN POR ROL
                if ($rol["tipo"] == "admin") {
                    header("Location: ../Admin/dashboard_admin.php");
                } else {
                    header("Location: ../Empleado/dashboard_empleado.php");
                }
                exit();

            } else {
                $error = "El usuario no tiene rol asignado";
            }

        } else {
            $error = "Contraseña incorrecta";
        }

    } else {
        $error = "Usuario no encontrado";
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

<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

<form method="POST">
<input class="input" type="text" name="usuario" placeholder="Usuario o correo" required>
<input class="input" type="password" name="clave" placeholder="Contraseña" required>

<button type="submit" class="login-btn">Entrar</button>
</form>

<a href="register.php" class="link">Registrarse</a>
<a href="../../index.php" class="link">Volver al inicio</a>

</div>

</body>
</html>