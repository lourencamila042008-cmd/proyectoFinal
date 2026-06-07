<?php
require_once __DIR__ . "/../../config/db.php";

$error   = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1️⃣ SANITIZAR
    $nombre_negocio   = trim($_POST["nombre_negocio"] ?? "");
    $nombre_usuario   = trim($_POST["nombre_usuario"] ?? "");
    $apellido_usuario = trim($_POST["apellido_usuario"] ?? "");
    $telefono         = trim($_POST["telefono"] ?? "");
    $correo           = trim($_POST["email"] ?? "");
    $password         = $_POST["password"] ?? "";
    $confirmar        = $_POST["confirmar"] ?? "";

    // 2️⃣ VALIDAR CAMPOS OBLIGATORIOS
    if (empty($nombre_negocio) || empty($nombre_usuario) || empty($apellido_usuario) || empty($correo) || empty($password)) {
        $error = "Todos los campos obligatorios deben estar completos.";

    // 3️⃣ VALIDAR CORREO
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";

    // 4️⃣ VALIDAR LONGITUDES
    } elseif (strlen($nombre_usuario) < 3) {
        $error = "El nombre de usuario debe tener al menos 3 caracteres.";

    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";

    // 5️⃣ VALIDAR QUE LAS CONTRASEÑAS COINCIDAN
    } elseif ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";

    // 6️⃣ VALIDAR TELÉFONO NUMÉRICO
    } elseif (!empty($telefono) && !ctype_digit($telefono)) {
        $error = "El teléfono solo debe contener números.";

    } else {

        $conn = Database::Conectar();

        // 7️⃣ VERIFICAR QUE EL CORREO NO ESTÉ REGISTRADO
        $stmtCheck = $conn->prepare("SELECT id_usuario FROM usuario WHERE correo = ? LIMIT 1");
        $stmtCheck->bind_param("s", $correo);
        $stmtCheck->execute();
        $stmtCheck->get_result()->num_rows > 0
            ? $error = "El correo ya está registrado. Intenta iniciar sesión."
            : null;
        $stmtCheck->close();

        // 8️⃣ VERIFICAR QUE EL NOMBRE DE USUARIO NO ESTÉ REGISTRADO
        if (empty($error)) {
            $stmtCheck2 = $conn->prepare("SELECT id_usuario FROM usuario WHERE nombre_usuario = ? LIMIT 1");
            $stmtCheck2->bind_param("s", $nombre_usuario);
            $stmtCheck2->execute();
            if ($stmtCheck2->get_result()->num_rows > 0) {
                $error = "El nombre de usuario ya está en uso. Elige otro.";
            }
            $stmtCheck2->close();
        }

        if (empty($error)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $telefono_int  = intval($telefono);

            $stmt = $conn->prepare("INSERT INTO usuario
                (nombre_usuario, apellido_usuario, telefono, correo, contrasena)
                VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $nombre_usuario, $apellido_usuario, $telefono_int, $correo, $password_hash);

            if ($stmt->execute()) {
                $id_usuario = $conn->insert_id;
                $stmt->close();

                // Asignar rol empleado
                $rolEmpleado = "empleado";
                $stmtRol = $conn->prepare("SELECT id_rol FROM rol WHERE tipo = ? LIMIT 1");
                $stmtRol->bind_param("s", $rolEmpleado);
                $stmtRol->execute();
                $resRol = $stmtRol->get_result();
                $stmtRol->close();

                if ($resRol->num_rows > 0) {
                    $rol    = $resRol->fetch_assoc();
                    $id_rol = $rol["id_rol"];

                    $stmtUserRol = $conn->prepare("INSERT INTO rol_user (id_rol, id_usuario) VALUES (?, ?)");
                    $stmtUserRol->bind_param("ii", $id_rol, $id_usuario);
                    $stmtUserRol->execute();
                    $stmtUserRol->close();

                    $success = "¡Cuenta creada correctamente! Ya puedes iniciar sesión.";
                } else {
                    $error = "Error: No existe el rol empleado en el sistema.";
                }
            } else {
                $error = "Error al registrar. Intenta nuevamente.";
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro</title>
<link rel="stylesheet" href="../../public/css/register.css">
</head>

<body>

<div class="register-box">
    <div class="logo">INVOICEPRO</div>
    <h2>Crear Cuenta</h2>

    <?php if ($error != ""): ?>
        <p style="color:red; text-align:center; font-size:14px; margin-bottom:10px;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <?php if ($success != ""): ?>
        <p style="color:green; text-align:center; font-size:14px; margin-bottom:10px;">
            ✅ <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input class="input" type="text" name="nombre_usuario"
               placeholder="Nombre de usuario *"
               value="<?= htmlspecialchars($_POST['nombre_usuario'] ?? '') ?>"
               maxlength="100" required>

        <input class="input" type="text" name="apellido_usuario"
               placeholder="Apellido *"
               value="<?= htmlspecialchars($_POST['apellido_usuario'] ?? '') ?>"
               maxlength="100" required>

        <input class="input" type="text" name="telefono"
               placeholder="Teléfono"
               value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
               maxlength="15">

        <input class="input" type="email" name="email"
               placeholder="Correo electrónico *"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               maxlength="100" required>

        <input class="input" type="password" name="password"
               placeholder="Contraseña * (mín. 6 caracteres)"
               maxlength="255" required>

        <input class="input" type="password" name="confirmar"
               placeholder="Confirmar contraseña *"
               maxlength="255" required>

        <button type="submit" class="register-btn">Registrarse</button>

    </form>

    <a href="login.php" class="link">¿Ya tienes cuenta? Inicia sesión</a>
    <a href="../../index.php" class="link">Volver al inicio</a>

</div>

</body>
</html>