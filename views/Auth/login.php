<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body{
    background:#2ecc71;
    font-family: Arial;
}

.login-box{
    width:300px;
    background:white;
    margin:100px auto;
    padding:20px;
    border-radius:8px;
    text-align:center;
}

input{
    width:90%;
    padding:10px;
    margin:10px 0;
}

button{
    background:#27ae60;
    color:white;
    border:none;
    padding:10px;
    width:100%;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="login-box">
<h2>Iniciar Sesión</h2>

<form action="" method="POST">
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="clave" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
</form>

</div>

</body>
</html>