
<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
body{
    font-family: Arial;
    background: #feb47b;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.form{
    background:white;
    padding:30px;
    border-radius:10px;
    width:300px;
}

input{
    width:100%;
    padding:8px;
    margin:10px 0;
}

button{
    width:100%;
    padding:10px;
    background:#feb47b;
    color:white;
    border:none;
    cursor:pointer;
}
</style>

</head>
<body>

<div class="form">
<h2>Login</h2>

<form method="POST">
<input type="text" name="usuario" placeholder="Usuario" required>
<input type="password" name="contraseña" placeholder="Contraseña" required>
<button type="submit">Ingresar</button>
</form>


</div>

</body>
</html>
