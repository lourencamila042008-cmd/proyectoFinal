<?php
require_once "../../../config/db.php";
$conn = Database::conectar();



/* ========= ACTUALIZAR ========= */
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];

    if(empty($nombre) || empty($cedula)){
        echo "<script>alert('Nombre y cédula son obligatorios');</script>";
    }else{

        $sql = "UPDATE clientes 
                SET nombre='$nombre', cedula='$cedula', telefono='$telefono'
                WHERE id_clientes=$id";

        if($conn->query($sql)){
            echo "<script>alert('Cliente actualizado'); window.location='clientes.php';</script>";
            exit;
        }else{
            die("Error SQL: " . $conn->error);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Cliente</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #2f7bbd, #3fa9f5);
    min-height: 100vh;
    padding: 40px;
}

/* CONTENEDOR */
.container{
    background:white;
    padding:35px;
    border-radius:15px;
    max-width:500px;
    margin:auto;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

/* TITULO */
h1{
    text-align:center;
    color:#0f4c81;
    margin-bottom:20px;
}

/* FORM */
form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

input{
    padding:12px;
    border-radius:8px;
    border:1px solid #ccc;
}

input:focus{
    border-color:#3fa9f5;
    outline:none;
}

/* BOTON */
button{
    background:#0f4c81;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#09365c;
}

/* MENSAJES */
.mensaje{
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
    text-align:center;
    font-weight:bold;
}

.error{
    background:#f8d7da;
    color:#721c24;
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

<h1>Editar Cliente</h1>

<?php if($mensaje != ""){ ?>
<div class="mensaje error"><?= $mensaje ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="nombre" value="<?= $cliente['nombre'] ?>" placeholder="Nombre completo">

<input type="text" name="cedula" value="<?= $cliente['cedula'] ?>" placeholder="Cédula">

<input type="text" name="telefono" value="<?= $cliente['telefono'] ?>" placeholder="Teléfono">

<button type="submit">Actualizar Cliente</button>

</form>

<a class="volver" href="clientes.php">⬅ Volver</a>

</div>

</body>
</html>