<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$resultado = $conn->query("SELECT * FROM usuario ORDER BY id_usuario DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios - InvoicePro</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter', sans-serif;
}

body{
    background:#f4f6f9;
    padding:30px;
    color:#1e293b;
}

.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:30px;
}

.top h1{
    font-size:34px;
    color:#0f172a;
}

.actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.btn-top{
    background:#17345f;
    color:white;
    padding:14px 18px;
    border-radius:14px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    transition:.3s;
}

.btn-top:hover{
    background:#264c83;
}

.btn-secondary{
    background:#64748b;
}

.btn-secondary:hover{
    background:#475569;
}

/* TABLE */

.table-container{
    background:white;
    border-radius:24px;
    overflow:hidden;
    border:1px solid #e2e8f0;
    box-shadow:0 4px 18px rgba(0,0,0,.04);
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

thead th{
    padding:20px;
    text-align:left;
    font-size:14px;
    color:#0f172a;
    font-weight:600;
}

tbody td{
    padding:20px;
    border-top:1px solid #e2e8f0;
    font-size:14px;
    color:#475569;
}

tbody tr:hover{
    background:#f8fafc;
}

/* ACTIONS */

.action-buttons{
    display:flex;
    gap:10px;
}

.btn{
    text-decoration:none;
    padding:10px 14px;
    border-radius:12px;
    font-size:13px;
    font-weight:600;
    transition:.3s;
}

.editar{
    background:#dbeafe;
    color:#2563eb;
}

.editar:hover{
    background:#bfdbfe;
}

.eliminar{
    background:#fee2e2;
    color:#dc2626;
}

.eliminar:hover{
    background:#fecaca;
}

.empty{
    text-align:center;
    padding:30px;
    color:#64748b;
}

/* RESPONSIVE */

@media(max-width:900px){

    body{
        padding:20px;
    }

    .top{
        flex-direction:column;
        align-items:flex-start;
    }

    .table-container{
        overflow-x:auto;
    }
}

</style>
</head>

<body>

<div class="top">

    <h1>Usuarios</h1>

    <div class="actions">

        <a href="../dashboard_admin.php"
        class="btn-top btn-secondary">
            ⬅ Volver
        </a>

        <a href="crear.php"
        class="btn-top">
            ➕ Nuevo Usuario
        </a>

    </div>

</div>

<div class="table-container">

<table>

<thead>
<tr>
<th>ID</th>
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

<td><?= htmlspecialchars($fila['nombre_usuario']) ?></td>

<td><?= htmlspecialchars($fila['apellido_usuario']) ?></td>

<td><?= $fila['telefono'] ?></td>

<td><?= htmlspecialchars($fila['correo']) ?></td>

<td>

<div class="action-buttons">

<a href="editar.php?id=<?= $fila['id_usuario'] ?>"
class="btn editar">
✏️ Editar
</a>

<a href="eliminar.php?id=<?= $fila['id_usuario'] ?>"
class="btn eliminar"
onclick="return confirm('¿Eliminar este usuario?')">
🗑️ Eliminar
</a>

</div>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="6" class="empty">
No hay usuarios registrados
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</body>
</html>