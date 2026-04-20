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
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #3fa9f5);
    padding: 40px;
    min-height: 100vh;
}

.contenedor {
    background: white;
    padding: 25px;
    border-radius: 12px;
    max-width: 1100px;
    margin: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    overflow-x: auto;
}

.top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

h1 { color: #0f4c81; }

.crear {
    background: #0f4c81;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

th {
    background: #0f4c81;
    color: white;
    padding: 12px;
    text-align: center;
}

td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

tr:hover { background: #f2f9ff; }

.btn {
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 13px;
    margin: 2px;
}

.editar  { background: #3fa9f5; }
.eliminar{ background: #e74c3c; }
</style>
</head>

<body>
<div class="contenedor">

    <div class="top">
        <h1>Usuarios</h1>
        <a href="crear.php" class="crear">+ Nuevo</a>
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