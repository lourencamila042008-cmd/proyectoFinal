<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$data = $conn->query("SELECT * FROM proveedores ORDER BY id_proveedores DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Proveedores</title>
<link rel="stylesheet" href="../../../public/css/proveedores/proveedores.css">
</head>

<body>
<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:#f4f7fb;
    color:#1e293b;
    padding:40px;
}

.container{
    max-width:1200px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(15,76,129,0.08);
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:15px;
}

.topbar h1{
    font-size:30px;
    color:#0f4c81;
    font-weight:700;
}

.acciones-top{
    display:flex;
    gap:12px;
}

.btn{
    background:#0f4c81;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    transition:0.2s;
}

.btn:hover{
    background:#1565a9;
    transform:translateY(-2px);
}

.btn-secundario{
    background:#e2e8f0;
    color:#0f4c81;
}

.btn-secundario:hover{
    background:#cbd5e1;
}

/* TABLA */

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

th{
    padding:16px;
    text-align:left;
    color:#64748b;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:.5px;
    border-bottom:1px solid #e2e8f0;
}

td{
    padding:18px 16px;
    border-bottom:1px solid #edf2f7;
    font-size:14px;
}

tr:hover{
    background:#f8fbff;
}

/* BOTONES TABLA */

.acciones{
    display:flex;
    gap:10px;
}

.btn-editar,
.btn-eliminar{
    text-decoration:none;
    padding:8px 12px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    transition:.2s;
}

.btn-editar{
    background:#dbeafe;
    color:#2563eb;
}

.btn-editar:hover{
    background:#bfdbfe;
}

.btn-eliminar{
    background:#fee2e2;
    color:#dc2626;
}

.btn-eliminar:hover{
    background:#fecaca;
}

.sin-datos{
    text-align:center;
    color:#94a3b8;
    padding:30px;
}

/* RESPONSIVE */

@media(max-width:768px){

    body{
        padding:20px;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
    }

    table{
        display:block;
        overflow-x:auto;
    }

}
</style>
<div class="container">

    <div class="topbar">
        <h1>Proveedores</h1>

        <div class="acciones-top">
            <a class="btn" href="crear_proveedor.php">+ Nuevo proveedor</a>
            <a class="btn btn-secundario" href="../dashboard_admin.php">⬅ Volver</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

        <?php if($data && $data->num_rows > 0){ ?>
            <?php while($p = $data->fetch_assoc()){ ?>

            <tr>
                <td><?= $p['id_proveedores'] ?></td>

                <td><?= htmlspecialchars($p['nombre']) ?></td>

                <td><?= htmlspecialchars($p['telefono']) ?></td>

                <td><?= htmlspecialchars($p['correo']) ?></td>

                <td class="acciones">

                    <a class="btn-editar"
                       href="editar_proveedor.php?id=<?= $p['id_proveedores'] ?>">
                       ✏️ Editar
                    </a>

                    <a class="btn-eliminar"
                       href="eliminar_proveedor.php?id=<?= $p['id_proveedores'] ?>"
                       onclick="return confirm('¿Eliminar proveedor?')">
                       🗑️ Eliminar
                    </a>

                </td>
            </tr>

            <?php } ?>
        <?php } else { ?>

            <tr>
                <td colspan="5" class="sin-datos">
                    No hay proveedores registrados
                </td>
            </tr>

        <?php } ?>

        </tbody>
    </table>

</div>

</body>
</html>