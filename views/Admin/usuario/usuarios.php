<?php
session_start();
require_once "../../../models/Usuario.php";

class UsuarioController{

    public function listar(){

        $model = new Usuario();

        $resultado = $model->obtenerUsuarios();

        require_once "views/Admin/usuario/usuarios.php";
    }

}
// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #3fa9f5);
    padding: 40px;
}

.contenedor{
    background: white;
    padding: 25px;
    border-radius: 12px;
    max-width: 1000px;
    margin: auto;
}

h1{
    text-align: center;
    color: #0f4c81;
}

.top{
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.crear{
    background: #0f4c81;
    color: white;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
}

table{
    width: 100%;
    border-collapse: collapse;
}

th{
    background: #0f4c81;
    color: white;
}

th, td{
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

tr:hover{
    background: #f2f9ff;
}

.btn{
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 13px;
}

/* ALERTAS */
.alert{
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
    color: white;
    font-weight: bold;
}

.success{ background: #2ecc71; }
.info{ background: #3498db; }
.danger{ background: #e74c3c; }


.editar{ background: #3fa9f5; }
.eliminar{ background: #e74c3c; }
</style>

</head>
<body>

<div class="contenedor">

<div class="top">
    <h1>Usuarios</h1>
    <a href="crear.php" class="crear">+ Nuevo</a>
</div>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Negocio</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Teléfono</th>
    <th>Correo</th>
    <th>Rol</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php if(isset($resultado) && $resultado->num_rows > 0){ ?>

    <?php while($fila = $resultado->fetch_assoc()){ ?>
        <tr>
            <td><?= $fila['id'] ?></td>
            <td><?= $fila['nombre_negocio'] ?></td>
            <td><?= $fila['nombre_usuario'] ?></td>
            <td><?= $fila['apellido_usuario'] ?></td>
            <td><?= $fila['telefono'] ?></td>
            <td><?= $fila['correo'] ?></td>
            <td><?= $fila['id_rol'] ?></td>
        </tr>
         <td>
            <a href="/MVC-PRU/index.php?controller=Usuario&action=editar&id=<?= $fila['id'] ?>" 
               class="btn editar">✏️</a>

            <a href="/MVC-PRU/index.php?controller=Usuario&action=eliminar&id=<?= $fila['id'] ?>" 
               class="btn eliminar"
               onclick="return confirm('¿Eliminar este usuario?')">🗑️</a>
        </td>

    <?php } ?>

<?php } else { ?>
    <tr>
        <td colspan="8">No hay usuarios</td>
    </tr>
<?php } ?>
</tbody>

</table>
</div>

</body>
</html>