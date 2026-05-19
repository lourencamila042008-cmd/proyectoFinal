<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";
$conn = Database::Conectar();

$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_facturas = intval($_POST['id_facturas']);
    $id_producto = intval($_POST['id_producto']);
    $motivo = $_POST['motivo'];
    $solucion = $_POST['solucion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $estado = "pendiente";

    // 🔥 VALIDAR TIEMPO DESDE BACKEND
    $stmt = $conn->prepare("
        SELECT fecha 
        FROM facturas 
        WHERE id_facturas=?
    ");

    $stmt->bind_param("i", $id_facturas);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_assoc();

    if($res){

        $fecha_factura = new DateTime($res['fecha']);
        $hoy = new DateTime();

        $dias = $fecha_factura->diff($hoy)->days;

        if($dias > 30){
            $errores[] = "❌ Garantía fuera de tiempo (más de 30 días)";
        }
    }

    // VALIDACIONES
    if ($id_facturas <= 0) {
        $errores[] = "Factura inválida";
    }

    if ($id_producto <= 0) {
        $errores[] = "Producto inválido";
    }

    if ($fecha_fin < $fecha_inicio) {
        $errores[] = "Fechas incorrectas";
    }

    // INSERTAR
    if(empty($errores)){

        $stmt = $conn->prepare("
            INSERT INTO garantias
            (id_facturas, id_producto, motivo, solucion, estado, fecha_inicio, fecha_fin)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iisssss",
            $id_facturas,
            $id_producto,
            $motivo,
            $solucion,
            $estado,
            $fecha_inicio,
            $fecha_fin
        );

        if($stmt->execute()){

            header("Location: iniciogarantias.php?msg=creado");
            exit;
        }
    }
}

// DATOS
$facturas = $conn->query("
    SELECT f.id_facturas, c.nombre
    FROM facturas f
    JOIN clientes c
    ON f.id_clientes = c.id_clientes
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Crear Garantía</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background:#f3f4f6;
    padding:30px;
    color:#0f172a;
}

/* HEADER */

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    gap:20px;
    flex-wrap:wrap;
}

.title h1{
    font-size:42px;
    font-weight:800;
    margin-bottom:5px;
}

.title p{
    color:#64748b;
    font-size:18px;
}

/* BOTONES */

.actions{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.btn{
    background:#16396b;
    color:white;
    text-decoration:none;
    padding:14px 22px;
    border-radius:14px;
    font-weight:600;
    transition:.3s;
    border:none;
    cursor:pointer;
}

.btn:hover{
    transform:translateY(-2px);
}

.btn-back{
    background:#64748b;
}

.btn-back:hover{
    background:#475569;
}

/* FORMULARIO */

.container{
    max-width:900px;
    margin:auto;
}

.form-box{
    background:white;
    border-radius:28px;
    padding:35px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group.full{
    grid-column:1/3;
}

label{
    margin-bottom:8px;
    font-weight:600;
    color:#334155;
}

input,
select{
    width:100%;
    padding:15px;
    border:none;
    border-radius:14px;
    background:#f8fafc;
    font-size:15px;
    outline:none;
    transition:.3s;
    border:2px solid transparent;
}

input:focus,
select:focus{
    border-color:#16396b;
    background:white;
}

/* ALERTAS */

.alert{
    background:#fee2e2;
    color:#b91c1c;
    padding:14px;
    border-radius:12px;
    margin-bottom:15px;
    font-weight:500;
}

/* INFO */

#info{
    margin-top:10px;
    padding:15px;
    border-radius:14px;
    background:#f8fafc;
    font-size:14px;
}

.bloqueado{
    color:#dc2626;
    font-weight:700;
}

.ok{
    color:#16a34a;
    font-weight:700;
}

/* FOOTER FORM */

.form-footer{
    margin-top:30px;
    display:flex;
    justify-content:flex-end;
}

/* RESPONSIVE */

@media(max-width:768px){

    body{
        padding:20px;
    }

    .header{
        flex-direction:column;
        align-items:flex-start;
    }

    .title h1{
        font-size:34px;
    }

    .form-grid{
        grid-template-columns:1fr;
    }

    .form-group.full{
        grid-column:auto;
    }

    .form-box{
        padding:25px;
    }

    .form-footer{
        justify-content:stretch;
    }

    .form-footer button{
        width:100%;
    }
}

</style>

</head>

<body>

<div class="container">

    <!-- HEADER -->

    <div class="header">

        <div class="title">
            <h1>Nueva Garantía</h1>
            <p>Registra una nueva solicitud de garantía.</p>
        </div>

        <div class="actions">

            <a class="btn btn-back"
            href="iniciogarantias.php">

                ← Volver

            </a>

        </div>

    </div>

    <!-- ERRORES -->

    <?php foreach($errores as $e): ?>

        <p class="alert"><?= $e ?></p>

    <?php endforeach; ?>

    <!-- FORM -->

    <div class="form-box">

        <form method="POST">

            <div class="form-grid">

                <!-- FACTURA -->

                <div class="form-group full">

                    <label>Factura</label>

                    <select name="id_facturas"
                    id="factura"
                    required>

                        <option value="">
                            Seleccionar factura
                        </option>

                        <?php while($f = $facturas->fetch_assoc()): ?>

                        <option value="<?= $f['id_facturas'] ?>">

                            Factura #<?= $f['id_facturas'] ?>
                            - <?= $f['nombre'] ?>

                        </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- PRODUCTO -->

                <div class="form-group full">

                    <label>Producto</label>

                    <select name="id_producto"
                    id="producto"
                    required>

                        <option value="">
                            Selecciona un producto
                        </option>

                    </select>

                    <div id="info"></div>

                </div>

                <!-- MOTIVO -->

                <div class="form-group">

                    <label>Motivo</label>

                    <select name="motivo">

                        <option value="daño">
                            Daño
                        </option>

                    </select>

                </div>

                <!-- SOLUCION -->

                <div class="form-group">

                    <label>Solución</label>

                    <select name="solucion">

                        <option value="cambio">
                            Cambio
                        </option>

                        <option value="reparacion">
                            Reparación
                        </option>

                        <option value="devolucion">
                            Devolución
                        </option>

                    </select>

                </div>

                <!-- FECHAS -->

                <div class="form-group">

                    <label>Fecha inicio</label>

                    <input type="date"
                    name="fecha_inicio"
                    required>

                </div>

                <div class="form-group">

                    <label>Fecha fin</label>

                    <input type="date"
                    name="fecha_fin"
                    required>

                </div>

            </div>

            <!-- BOTON -->

            <div class="form-footer">

                <button type="submit"
                class="btn"
                id="btnGuardar">

                    Guardar garantía

                </button>

            </div>

        </form>

    </div>

</div>

<script>

let productos = [];

// FACTURA

document.getElementById("factura")
.addEventListener("change", function(){

    fetch(
        "obtener_productos.php?id_factura=" + this.value
    )

    .then(res => res.json())

    .then(data => {

        productos = data;

        let select =
        document.getElementById("producto");

        select.innerHTML = "";

        data.forEach(p => {

            let opt =
            document.createElement("option");

            opt.value = p.id_productos;

            opt.textContent = p.nombre;

            select.appendChild(opt);

        });

    });

});

// PRODUCTO

document.getElementById("producto")
.addEventListener("change", function(){

    let p = productos.find(
        x => x.id_productos == this.value
    );

    let info =
    document.getElementById("info");

    let btn =
    document.getElementById("btnGuardar");

    if(p.fuera_tiempo){

        info.innerHTML = `
        <p class='bloqueado'>
        ⚠ Producto fuera de garantía
        (${p.dias} días)
        </p>
        `;

        btn.disabled = true;

    } else {

        info.innerHTML = `
        <p class='ok'>
        ✔ Dentro de garantía
        (${p.dias} días)
        </p>
        `;

        btn.disabled = false;
    }

});

</script>

</body>
</html>