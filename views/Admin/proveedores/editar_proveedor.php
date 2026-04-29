<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$id = $_GET['id'];
$p = $conn->query("SELECT * FROM proveedores WHERE id_proveedores=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<style>
body{font-family:Arial;background:#f4f6f9;padding:40px;}
.container{background:white;padding:30px;border-radius:10px;max-width:500px;margin:auto;}
input{width:100%;padding:10px;margin:10px 0;}
button{background:#0f4c81;color:white;padding:10px;border:none;}
</style>
</head>

<body>

<div class="container">

<h2>Editar Proveedor</h2>

<form method="POST" action="actualizar_proveedor.php">
<input type="hidden" name="id" value="<?= $p['id_proveedores'] ?>">
<input type="text" name="nombre" value="<?= $p['nombre'] ?>">
<input type="text" name="telefono" value="<?= $p['telefono'] ?>">
<input type="email" name="correo" value="<?= $p['correo'] ?>">
<button>Actualizar</button>
</form>

</div>

</body>
</html>