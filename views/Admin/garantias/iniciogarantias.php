<?php
require_once "../../../config/db.php";

$conn = Database::Conectar();

$garantias = $conn->query("
    SELECT g.*, p.nombre AS nombre_producto
    FROM garantias g
    JOIN productos p ON g.id_producto = p.id_productos
    ORDER BY g.id_garantia DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Garantías</title>
<link rel="stylesheet" href="../../../public/css/garantias/garantias.css">

</head>

<body>
<div class="container">

    <div class="topbar">
        <h1>Garantías</h1>
        <a class="btn" href="crear_garantia.php">+ Nueva garantía</a>
        <a class="btn" href="../dashboard_admin.php">volver al inicio</a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == "creado"){ ?>
        <div class="alert success">Garantía creada correctamente ✅</div>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Factura</th>
                <th>Producto</th>
                <th>Motivo</th>
                <th>Solución</th>
                <th>Estado</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php while($g = $garantias->fetch_assoc()){ ?>
            <tr>
                <td><?= $g['id_garantia'] ?></td>
                <td>#<?= $g['id_facturas'] ?></td>
                <td><?= htmlspecialchars($g['nombre_producto']) ?></td>
                <td><?= ucfirst($g['motivo']) ?></td>
                <td><?= ucfirst($g['solucion']) ?></td>
                <td>
                    <?php
                    if($g['estado'] == 'pendiente'){
                        echo "<span class='estado estado-pendiente'>Pendiente</span>";
                    } elseif($g['estado'] == 'en_revision'){
                        echo "<span class='estado estado-revision'>En revisión</span>";
                    } else {
                        echo "<span class='estado estado-resuelto'>Resuelto</span>";
                    }
                    ?>
                </td>
                <td><?= $g['fecha_inicio'] ?></td>
                <td><?= $g['fecha_fin'] ?></td>
                <td>
                    <a class="btn-ver" href="ver_garantia.php?id=<?= $g['id_garantia'] ?>">Ver</a>
                    
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</body>
</html>