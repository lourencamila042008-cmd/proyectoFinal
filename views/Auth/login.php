<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    $conn = Database::Conectar();

    // 🔹 Buscar usuario
    $sql = "SELECT * FROM usuario 
            WHERE correo = '$usuario' OR nombre_usuario = '$usuario'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if ($user["contraseña"] == $clave) {

            $_SESSION["id_usuario"] = $user["id_usuario"];

            // 🔥 OBTENER ROL (VERSIÓN SEGURA)
            $sqlRol = "SELECT r.tipo, r.id_rol
                       FROM rol_user ru
                       INNER JOIN rol r ON ru.id_rol = r.id_rol
                       WHERE ru.id_usuario = ".$user["id_usuario"];

            $resRol = $conn->query($sqlRol);

            if($resRol->num_rows > 0){

                $rol = $resRol->fetch_assoc();

                $_SESSION["rol"] = $rol["tipo"];

                // 🔥 REDIRECCIÓN CORRECTA
                if ($rol["id_rol"] == 1) {
                    header("Location: ../Admin/dashboard_admin.php");
                } else {
                    header("Location: ../Empleado/dashboard.php");
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
<input class="input" type="text" name="usuario" placeholder="Usuario" required>
<input class="input" type="password" name="clave" placeholder="Contraseña" required>

<button type="submit" class="login-btn">Entrar</button>
</form>

<a href="register.php" class="link">Registrarse</a>
<a href="../../index.php" class="link">Volver al inicio</a>

</div>

</body>
</html>