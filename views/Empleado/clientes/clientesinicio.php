<?php
session_start();

// 🔐 SOLO EMPLEADOS
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../models/clientes.php";

$model = new Cliente();

$errores = [];

// 🔥 GUARDAR CLIENTE
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre   = trim($_POST['nombre']);
    $cedula   = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);

    // =========================
    // VALIDACIONES
    // =========================

    if(empty($nombre)){
        $errores[] = "El nombre es obligatorio";
    }

    if(strlen($nombre) < 3){
        $errores[] = "El nombre es demasiado corto";
    }

    if(empty($cedula)){
        $errores[] = "La cédula es obligatoria";
    }

    if(!is_numeric($cedula)){
        $errores[] = "La cédula debe ser numérica";
    }

    if(!empty($telefono) && !is_numeric($telefono)){
        $errores[] = "El teléfono debe ser numérico";
    }

    // =========================
    // VALIDAR DUPLICADO
    // =========================

    if(empty($errores)){

        require_once "../../../config/db.php";

        $conn = Database::Conectar();

        $stmt = $conn->prepare("
        SELECT id_clientes 
        FROM clientes 
        WHERE cedula = ?
        ");

        $stmt->bind_param("s", $cedula);

        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $errores[] = "La cédula ya existe";
        }

        $stmt->close();
    }

    // =========================
    // GUARDAR
    // =========================

    if(empty($errores)){

        $guardar = $model->crear(
            $nombre,
            $cedula,
            $telefono
        );

        if($guardar){

            echo "
            <script>
            alert('Cliente guardado correctamente');
            window.location='clientes_empleado.php';
            </script>
            ";

            exit();

        } else {

            $errores[] = "Error al guardar cliente";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Clientes</title>

<style>

/* 🌑 FONDO */
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:linear-gradient(135deg,#0b1f3a,#162c4f);
    color:white;
}

/* 📦 CONTENEDOR */
.container{
    width:90%;
    max-width:1000px;
    margin:40px auto;
}

/* 🔝 TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.topbar h1{
    color:#60a5fa;
}

/* 🧾 CARD */
.card{
    background:#1e293b;
    padding:25px;
    border-radius:15px;
    box-shadow:0 15px 40px rgba(0,0,0,0.4);
}

/* FORM */
form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

.full{
    grid-column:1 / 3;
}

/* INPUTS */
input{
    padding:14px;
    border-radius:10px;
    border:1px solid #334155;
    background:#0f172a;
    color:white;
    font-size:14px;
}

input:focus{
    outline:none;
    border-color:#60a5fa;
    box-shadow:0 0 8px rgba(96,165,250,0.3);
}

/* BOTÓN */
button{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    font-weight:bold;
    transition:0.3s;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,0.4);
}

/* TABLA */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}

th{
    background:#2563eb;
    padding:14px;
}

td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #334155;
}

tr:hover{
    background:#0f172a;
}

/* ALERTAS */
.alert{
    background:rgba(239,68,68,0.15);
    color:#f87171;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
}

/* BOTÓN VOLVER */
.btn-volver{
    display:inline-block;
    margin-top:20px;
    background:#334155;
    color:white;
    padding:12px 16px;
    border-radius:10px;
    text-decoration:none;
    transition:0.3s;
}

.btn-volver:hover{
    background:#475569;
}

</style>

</head>

<body>

<div class="container">

<div class="topbar">

<h1>Clientes</h1>

</div>

<div class="card">

<!-- 🔥 ERRORES -->
<?php if(!empty($errores)): ?>

    <?php foreach($errores as $e): ?>

        <div class="alert">

            <?= htmlspecialchars($e) ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

<!-- 🔥 FORMULARIO -->
<form method="POST">

<input
type="text"
name="nombre"
placeholder="Nombre completo"
required
>

<input
type="text"
name="cedula"
placeholder="Cédula"
required
>

<input
type="text"
name="telefono"
placeholder="Teléfono"
class="full"
>

<button type="submit" class="full">

Guardar Cliente

</button>

</form>

<!-- 🔥 TABLA SOLO VISUAL -->
<?php

require_once "../../../config/db.php";

$conn = Database::Conectar();

$clientes = $conn->query("
SELECT * FROM clientes
ORDER BY id_clientes DESC
");

?>

<table>

<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Cédula</th>
<th>Teléfono</th>
</tr>
</thead>

<tbody>

<?php while($c = $clientes->fetch_assoc()){ ?>

<tr>

<td><?= $c['id_clientes'] ?></td>

<td><?= htmlspecialchars($c['nombre']) ?></td>

<td><?= htmlspecialchars($c['cedula']) ?></td>

<td><?= htmlspecialchars($c['telefono']) ?></td>

</tr>

<?php } ?>

</tbody>

</table>

<a href="../dashboard_empleado.php" class="btn-volver">

⬅ Volver al inicio

</a>

</div>

</div>

</body>
</html>