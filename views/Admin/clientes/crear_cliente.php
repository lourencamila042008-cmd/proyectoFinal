<?php

require_once "../../../models/clientes.php";

$model = new Cliente();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];

    $guardar = $model->crear($nombre, $cedula, $telefono);

    if($guardar){
        echo "<script>alert('Cliente guardado'); window.location='clientes.php';</script>";
    } else {
        echo "Error al guardar";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Cliente</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #2f7bbd, #3fa9f5);
    min-height: 100vh;
    padding: 40px;
    overflow-y:auto;
    position: relative;
}

/* DIFUMINADO */
body::before, body::after{
    content:"";
    position:absolute;
    border-radius:50%;
    background:rgba(255,255,255,0.1);
    filter:blur(120px);
}
body::before{ width:500px; height:500px; top:-100px; left:-100px; }
body::after{ width:400px; height:400px; bottom:-100px; right:-100px; }

.container{
    background:white;
    padding:35px;
    border-radius:15px;
    max-width:500px;
    margin:auto;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    position:relative;
    z-index:1;
}

h1{
    text-align:center;
    color:#0f4c81;
    margin-bottom:20px;
}

form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

input{
    padding:12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:14px;
}

input:focus{
    border-color:#3fa9f5;
    outline:none;
    box-shadow:0 0 6px rgba(63,169,245,0.5);
}

button{
    background:#0f4c81;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#09365c;
}

.volver{
    display:block;
    margin-top:10px;
    text-align:center;
    text-decoration:none;
    color:#0f4c81;
    font-weight:bold;
}
</style>
</head>


<body>

<div class="container">

<h1>Nuevo Cliente</h1>

<form method="POST">

<input type="text" name="nombre" placeholder="Nombre completo" required>

<input type="text" name="cedula" placeholder="Cédula" required>

<input type="text" name="telefono" placeholder="Teléfono">

<button type="submit">Guardar Cliente</button>

</form>

<a class="volver" href="clientes.php">⬅ Volver</a>

</div>

</body>
</html>