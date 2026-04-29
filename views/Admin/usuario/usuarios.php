<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$resultado = $conn->query("SELECT * FROM usuario ORDER BY id_usuario DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>
<link rel="stylesheet" href="../../../public/css/usuarios/usuarios.css">

</head>

<body>
<div class="contenedor">

    <div class="top">
        <h1>Usuarios</h1>
        <a href="crear.php" class="crear">+ Nuevo</a>
        <a class="crear" href="../dashboard_admin.php">volver al inicio</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Negocio</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $fila['id_usuario'] ?></td>
                <td><?= htmlspecialchars($fila['nombre_negocio']) ?></td>
                <td><?= htmlspecialchars($fila['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($fila['apellido_usuario']) ?></td>
                <td><?= $fila['telefono'] ?></td>
                <td><?= htmlspecialchars($fila['correo']) ?></td>
                <td>
                    <a href="editar.php?id=<?= $fila['id_usuario'] ?>" class="btn editar">✏️ Editar</a>
                    <a href="eliminar.php?id=<?= $fila['id_usuario'] ?>"
                       class="btn eliminar"
                       onclick="return confirm('¿Eliminar este usuario?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No hay usuarios registrados</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>