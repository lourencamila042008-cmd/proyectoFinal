<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Usuario</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #3fa9f5);
    padding: 40px;
}

.contenedor{
    background: white;
    padding: 30px;
    border-radius: 15px;
    max-width: 600px;
    margin: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

h1{
    text-align: center;
    color: #0f4c81;
}

form{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

input{
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button{
    grid-column: span 2;
    background: #0f4c81;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
}

button:hover{
    background: #09365c;
}
</style>

</head>
<body>

<div class="contenedor">

<h1>Crear Usuario</h1>

<!-- 🔥 ESTA LÍNEA ES LA CLAVE -->
<form>

<input type="text" name="nombre_negocio" placeholder="Negocio" required>
<input type="text" name="nombre_usuario" placeholder="Nombre" required>
<input type="text" name="apellido_usuario" placeholder="Apellido" required>
<input type="text" name="telefono" placeholder="Teléfono">
<input type="email" name="correo" placeholder="Correo">
<input type="text" name="id_rol" placeholder="Rol">
<input type="password" name="contraseña" placeholder="Contraseña">

<button onclick="location.href='usuarios.php'">Guardar Usuario</button>

</form>

</div>

</body>
</html>