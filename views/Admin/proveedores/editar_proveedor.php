<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$id = intval($_GET['id']);

$p = $conn->query("
    SELECT * 
    FROM proveedores 
    WHERE id_proveedores = $id
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Proveedor</title>
<link rel="stylesheet" href="../../../public/css/proveedores/editar_proveedor.css">
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

    <h1>Editar Proveedor</h1>

    <form method="POST" action="actualizar_proveedor.php">

        <input type="hidden"
               name="id"
               value="<?= $p['id_proveedores'] ?>">

        <label>Nombre</label>
        <input type="text"
               name="nombre"
               value="<?= htmlspecialchars($p['nombre']) ?>"
               required>

        <label>Teléfono</label>
        <input type="text"
               name="telefono"
               value="<?= htmlspecialchars($p['telefono']) ?>"
               required>

        <label>Correo</label>
        <input type="email"
               name="correo"
               value="<?= htmlspecialchars($p['correo']) ?>"
               required>

        <button type="submit">Actualizar Proveedor</button>

    </form>

    <a class="volver" href="proveedores.php">⬅ Volver</a>

</div>

</body>
</html>