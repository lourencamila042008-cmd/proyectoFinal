<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
.login-body{
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
  background:#f4f8ff;
}

.login-box{
  background:white;
  padding:40px;
  border-radius:18px;
  width:320px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  text-align:center;
}

.login-box h1{
  color:#1e5eff;
  margin-bottom:25px;
}

.input{
  width:100%;
  padding:12px;
  margin:10px 0;
  border:1px solid #dbe5ff;
  border-radius:8px;
}

.login-btn{
  width:100%;
  background:#1e5eff;
  color:white;
  border:none;
  padding:12px;
  border-radius:8px;
  margin-top:15px;
  font-weight:bold;
}

.logo{
  font-size:22px;
  font-weight:bold;
  color:#1e5eff;
  margin-bottom:15px;
}

.login-btn{
  display:block;
  width:100%;
  background:#1e5eff;
  color:white;
  text-decoration:none;
  padding:12px;
  border-radius:8px;
  margin-top:15px;
  font-weight:bold;
}
</style>
</head>

<body class="login-body"> 

<div class="login-box">
    <div class="logo">INVOICEPRO</div>
<h2>Iniciar Sesión</h2>

<form action="" method="POST">
    <input class="input" type="text" name="usuario" placeholder="Usuario" required>
    <input class="input" type="password" name="clave" placeholder="Contraseña" required>
    <button type="submit" class="login-btn">Entrar</button>
    <a href="registro.php" class="">Registrarse</a>
</form>

</div>

</body>
</html>