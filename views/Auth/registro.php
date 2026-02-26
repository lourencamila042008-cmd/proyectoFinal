
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <style>
        .register-body{
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
  background:#f4f8ff;
}

.register-box{
  background:white;
  padding:40px;
  border-radius:18px;
  width:340px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  text-align:center;
}

.register-box h1{
  color:#1e5eff;
  margin-bottom:20px;
}

.input{
  width:100%;
  padding:12px;
  margin:8px 0;
  border:1px solid #dbe5ff;
  border-radius:8px;
}

.register-btn{
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

.login-link{
  display:block;
  margin-top:15px;
  color:#1e5eff;
  text-decoration:none;
  font-size:14px;
}

.logo{
  font-size:22px;
  font-weight:bold;
  color:#1e5eff;
  margin-bottom:15px;
}
    </style>
</head>
<body>

<div class="register-box">
    <div class="logo">INVOICEPRO</div>
    <h2>Crear Cuenta</h2>


    <form method="POST">
        <input class="input" type="text" name="nombre_negocio" placeholder="nombre del negocio" required>
        <input class="input" type="text" name="nombre_usuario" placeholder="Nombre completo" required>
        <input class="input" type="text" name="apellido_usuario" placeholder="apellido completo" required>
        <input class="input" type="text" name="telefono" placeholder="numero de telefono" required>
        <input class="input" type="email" name="email" placeholder="Correo electrónico" required>
        <input class="input" type="password" name="password" placeholder="Contraseña" required>
        <select class="input" name="rol">
            <option value="1">rol</option>
         <option value="2">administrador</option>
         <option value="3">empleado</option>
        </select>
        <button type="submit">Registrarse</button>
    </form>

    <a href="index.php">¿Ya tienes cuenta? Inicia sesión</a>
</div>

</body>
</html>
