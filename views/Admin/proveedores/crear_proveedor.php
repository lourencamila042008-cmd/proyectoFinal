<?php
require_once "../../../config/db.php";

$errores = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Proveedor</title>
<link rel="stylesheet" href="../../../public/css/proveedores/crear_proveedor.css">
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
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:30px;
}

.container{
    width:100%;
    max-width:520px;
    background:white;
    padding:35px;
    border-radius:22px;
    box-shadow:0 10px 30px rgba(15,76,129,.08);
}

h1{
    text-align:center;
    margin-bottom:30px;
    color:#0f4c81;
}

form{
    display:flex;
    flex-direction:column;
    gap:18px;
}

label{
    font-size:14px;
    font-weight:600;
    color:#334155;
}

input{
    width:100%;
    padding:14px;
    border:1px solid #dbe2ea;
    border-radius:12px;
    font-size:14px;
    background:#f8fafc;
}

input:focus{
    outline:none;
    border-color:#0f4c81;
    background:white;
}

button{
    background:#0f4c81;
    color:white;
    border:none;
    padding:14px;
    border-radius:14px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

button:hover{
    background:#1565a9;
    transform:translateY(-2px);
}

.volver{
    display:block;
    text-align:center;
    margin-top:20px;
    text-decoration:none;
    color:#64748b;
    font-weight:600;
}

.volver:hover{
    color:#0f4c81;
}
    </style>

<div class="container">

    <h1>Nuevo Proveedor</h1>

    <?php if(!empty($errores)): ?>
        <div class="mensaje">
            <?php foreach($errores as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="guardar_proveedor.php">

        <label>Nombre</label>
        <input type="text"
               name="nombre"
               placeholder="Nombre del proveedor"
               required>

        <label>Teléfono</label>
        <input type="text"
               name="telefono"
               placeholder="Teléfono"
               required>

        <label>Correo</label>
        <input type="email"
               name="correo"
               placeholder="correo@ejemplo.com"
               required>

        <button type="submit">Guardar Proveedor</button>

    </form>

    <a class="volver" href="proveedores.php">⬅ Volver</a>

</div>

</body>
</html>