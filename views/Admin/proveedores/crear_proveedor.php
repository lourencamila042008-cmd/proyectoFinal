<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Proveedor</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg,#0f4c81,#2f7bbd,#3fa9f5);
    min-height: 100vh;
    padding:40px;
}
.container{
    background:white;
    padding:30px;
    border-radius:15px;
    max-width:500px;
    margin:auto;
}
h1{ text-align:center; color:#0f4c81; }
form{ display:flex; flex-direction:column; gap:10px; }
input{ padding:12px; border-radius:8px; border:1px solid #ccc; }
button{
    background:#0f4c81;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
}
</style>

</head>
<body>

<div class="container">
<h1>Nuevo Proveedor</h1>

<form method="POST" action="guardar_proveedor.php">
<input type="text" name="nombre" placeholder="Nombre" required>
<input type="text" name="telefono" placeholder="Teléfono" required>
<input type="email" name="correo" placeholder="Correo" required>
<button>Guardar</button>
</form>

</div>
</body>
</html>