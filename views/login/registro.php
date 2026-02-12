<?php
include("../../config/db.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre_negocio = $_POST["nombre_negocio"];
    $nombre = $_POST["nombre_usuario"];
    $apellido = $_POST["apellido_usuario"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $id_rol = $_POST["rol"];

    // Encriptar contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $verificar = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $verificar->bind_param("s", $email);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "El correo ya está registrado";
    } else {
        // Insertar usuario
        $sql = $conn->prepare("INSERT INTO usuarios (nombre_negocio, nombre_usuario, apellido_usuario, telefono, email, password, rol) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $nombre_negocio,$nombre_usuario, $apellido_usuario, $telefono, $email, $passwordHash, $id_rol);

        if ($sql->execute()) {
            $mensaje = "Registro exitoso 🎉";
        } else {
            $mensaje = "Error al registrar";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #36b9cc, #4e73df);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .registro-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
        }

        .registro-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #2e59d9;
        }

        .mensaje {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            color: green;
        }

        .error {
            color: red;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="registro-box">
    <h2>Crear Cuenta</h2>

    <?php if($mensaje != ""): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nombre_negocio" placeholder="nombre del negocio" required>
        <input type="text" name="nombre_usuario" placeholder="Nombre completo" required>
        <input type="text" name="apellido_usuario" placeholder="apellido completo" required>
        <input type="text" name="telefono" placeholder="numero de telefono" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select>
            <option value="1">rol</option>
         <option value="2">administrador</option>
         <option value="3">empleado</option>
        </select>
        <button type="submit">Registrarse</button>
    </form>

    <a href="index.php">¿Ya tienes cuenta? Inicia sesión</a>
</div>

</body>
</html>
