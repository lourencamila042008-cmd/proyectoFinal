<?php

require_once "../../../models/clientes.php";

$model = new Cliente();

$errores = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
 // VALIDACIONES

      if (empty($nombre)) {
    $errores[] = "El nombre es obligatorio";
} elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
    $errores[] = "El nombre solo puede contener letras y espacios";
}

    // Cédula
    if (empty($cedula)) {
        $errores[] = "La cédula es obligatoria";
    } elseif (!ctype_digit($cedula)) {
        $errores[] = "La cédula solo debe contener números";
    } elseif (strlen($cedula) < 6 || strlen($cedula) > 15) {
        $errores[] = "La cédula no tiene un formato válido";
    }

    // Teléfono (opcional pero validado)
    if (!empty($telefono)) {
        if (!ctype_digit($telefono)) {
            $errores[] = "El teléfono solo debe contener números";
        } elseif (strlen($telefono) < 7 || strlen($telefono) > 15) {
            $errores[] = "El teléfono no es válido";
        }
    }

    // SI TODO ESTÁ BIEN
    if (empty($errores)) {

        $guardar = $model->crear($nombre, $cedula, $telefono);

        if($guardar){
            echo "<script>alert('Cliente guardado'); window.location='clientes.php';</script>";
        } else {
            $errores[] = "Error al guardar el cliente";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Cliente</title>
<link rel="stylesheet" href="../../../public/css/clientes/crear_cliente.css">
</head>


<body>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f4f7fb;
    font-family:'Inter',sans-serif;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.container{
    width:100%;
    max-width:650px;
    background:white;
    padding:40px;
    border-radius:24px;
    box-shadow:0 10px 30px rgba(15,23,42,.08);
}

h1{
    font-size:30px;
    margin-bottom:25px;
    color:#0f172a;
}

form{
    display:flex;
    flex-direction:column;
    gap:18px;
}

input{
    width:100%;
    padding:15px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    background:#f8fafc;
    font-size:14px;
    transition:.3s;
}

input:focus{
    outline:none;
    border-color:#2563eb;
    background:white;
}

button{
    background:#1e3a5f;
    color:white;
    border:none;
    padding:15px;
    border-radius:14px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#16304d;
    transform:translateY(-2px);
}

.volver{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#475569;
    font-size:14px;
    font-weight:500;
}

.volver:hover{
    color:#2563eb;
}

.errores{
    background:#fee2e2;
    color:#b91c1c;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:14px;
}
</style>
<div class="container">

<h1>Nuevo Cliente</h1>

<?php if (!empty($errores)): ?>
    <div style="color:red;">
        <?php foreach($errores as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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