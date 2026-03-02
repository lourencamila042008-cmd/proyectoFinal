<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "empleado") {
    header("Location: ../Auth/login.php");
    exit();
}
?>

<h1>Panel de Empleado</h1>
<a href="../Auth/logout.php">Cerrar sesión</a>