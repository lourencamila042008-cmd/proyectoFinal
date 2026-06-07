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

// GUARDAR CLIENTE
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre   = trim($_POST['nombre']);
    $cedula   = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);

    // VALIDACIONES

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

    // VALIDAR DUPLICADO

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

    // GUARDAR

    if(empty($errores)){

        $guardar = $model->crear(
            $nombre,
            $cedula,
            $telefono
        );

        if($guardar){

            header("Location: clientesinicio.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Crear Cliente</title>

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

/* CONTAINER */

.container{
    max-width:850px;
    margin:auto;
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

/* CARD */

.card{
    background:white;
    border-radius:28px;
    padding:35px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

/* FORM */

form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.full{
    grid-column:1 / 3;
}

/* INPUTS */

input{
    width:100%;
    padding:16px;
    border:none;
    border-radius:16px;
    background:#f8fafc;
    font-size:15px;
    outline:none;
    transition:.3s;
    border:2px solid transparent;
}

input:focus{
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

/* FOOTER */

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

    form{
        grid-template-columns:1fr;
    }

    .full{
        grid-column:auto;
    }

    .card{
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

            <h1>Nuevo Cliente</h1>

            <p>
                Registra un nuevo cliente en el sistema.
            </p>

        </div>

        <div class="actions">

            <a href="clientes_empleado.php"
            class="btn btn-back">

                ← Volver

            </a>

        </div>

    </div>

    <!-- CARD -->

    <div class="card">

        <!-- ERRORES -->

        <?php if(!empty($errores)): ?>

            <?php foreach($errores as $e): ?>

                <div class="alert">

                    <?= htmlspecialchars($e) ?>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <!-- FORM -->

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

            <div class="form-footer full">

                <button type="submit"
                class="btn">

                    Guardar Cliente

                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>