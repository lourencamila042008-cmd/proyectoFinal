<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

// Obtener ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: garantias.php");
    exit;
}
$id_garantia = $_GET['id'];

// Consultar datos
$sql = "SELECT g.*, p.nombre AS nombre_producto FROM garantias g JOIN productos p ON g.id_producto = p.id_productos WHERE g.id_garantia = '$id_garantia'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: garantias.php?msg=no_existe");
    exit;
}

$garantia = $result->fetch_assoc();

// PROCESAR FORMULARIO
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir datos
    $id_facturas = $_POST['id_facturas'];
    $id_producto = $_POST['id_producto'];
    $motivo = $_POST['motivo'];
    $solucion = $_POST['solucion'];
    $estado = $_POST['estado'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // ✅ CONSULTA DIRECTA Y LIMPIA - SIN ERRORES POSIBLES
    $actualizar = "UPDATE garantias SET 
        id_facturas = '$id_facturas',
        id_producto = '$id_producto',
        motivo = '$motivo',
        solucion = '$solucion',
        estado = '$estado',
        fecha_inicio = '$fecha_inicio',
        fecha_fin = '$fecha_fin'
        WHERE id_garantia = '$id_garantia'";

    // EJECUTAR
    if ($conn->query($actualizar) === TRUE) {
        header("Location: garantias.php?msg=editado");
        exit;
    } else {
        // Si falla, te dirá exactamente por qué
        echo "ERROR: " . $conn->error;
    }
}

// Obtener productos
$productos = $conn->query("SELECT id_productos, nombre FROM productos ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Garantía</title>
    <link rel="stylesheet" href="../../../public/css/garantias/garantias.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fb;
            color: #1e293b;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .topbar h1 {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
        }

        .btn {
            background: #1e3a5f;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            transition: .3s;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #16304d;
            transform: translateY(-2px);
        }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .error {
            background: #fecdd3;
            color: #991b1b;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, .06);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            color: #1e293b;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #1e3a5f;
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <h1>Editar Garantía</h1>
            <a class="btn" href="garantias.php">← Volver al listado</a>
        </div>

        <div class="card">
            <form method="POST" action="">
                <div class="row">
                    <div class="form-group">
                        <label for="id_facturas">Número de Factura</label>
                        <input type="number" id="id_facturas" name="id_facturas" value="<?= $garantia['id_facturas'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="id_producto">Producto</label>
                        <select id="id_producto" name="id_producto" required>
                            <option value="">Seleccionar producto</option>
                            <?php while ($prod = $productos->fetch_assoc()): ?>
                                <option value="<?= $prod['id_productos'] ?>" 
                                    <?= ($prod['id_productos'] == $garantia['id_producto']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prod['nombre']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- MOTIVO: Aquí está la opción "Daño" como valor, tal como tú lo usas -->
                <div class="form-group">
                    <label for="motivo">Motivo de la garantía</label>
                    <select id="motivo" name="motivo" required>
                        <option value="">Seleccionar motivo</option>
                        <option value="Daño" <?= ($garantia['motivo'] == 'Daño') ? 'selected' : '' ?>>Daño</option>
                        <option value="Defecto" <?= ($garantia['motivo'] == 'Defecto') ? 'selected' : '' ?>>Defecto</option>
                        <option value="Fallo" <?= ($garantia['motivo'] == 'Fallo') ? 'selected' : '' ?>>Fallo</option>
                        <option value="Otro" <?= ($garantia['motivo'] == 'Otro') ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="solucion">Solución aplicada</label>
                    <textarea id="solucion" name="solucion" rows="3"><?= htmlspecialchars($garantia['solucion']) ?></textarea>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" required>
                            <option value="pendiente" <?= ($garantia['estado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                            <option value="en_revision" <?= ($garantia['estado'] == 'en_revision') ? 'selected' : '' ?>>En revisión</option>
                            <option value="resuelto" <?= ($garantia['estado'] == 'resuelto') ? 'selected' : '' ?>>Resuelto</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= $garantia['fecha_inicio'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de fin</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?= $garantia['fecha_fin'] ?>">
                    </div>
                </div>

                <button type="submit" class="btn">Guardar cambios</button>
            </form>
        </div>
    </div>
</body>
</html>