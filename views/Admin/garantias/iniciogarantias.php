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
<link rel="stylesheet" href="../../../public/css/garantias.css">
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #2f7bbd, #3fa9f5);
    min-height: 100vh;
    padding: 40px;
}

.container {
    background: white;
    padding: 30px;
    border-radius: 15px;
    max-width: 1100px;
    margin: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    overflow-x: auto;
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

h1 { color: #0f4c81; }

.btn {
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

.estado {
    padding: 5px 10px;
    border-radius: 6px;
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.estado-pendiente  { background: #f39c12; }
.estado-revision   { background: #3498db; }
.estado-resuelto   { background: #2ecc71; }

.btn-ver {
    background: #3498db;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}

.alert {
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: bold;
}

.success {
    background: #d4edda;
    color: #155724;
}
</style>
</head>

<body>
<div class="container">

    <div class="topbar">
        <h1>Garantías</h1>
        <a class="btn" href="crear_garantia.php">+ Nueva garantía</a>
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