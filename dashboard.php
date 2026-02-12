<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Bienvenida, <?php echo $_SESSION["usuario"]; ?> 🎉</h1>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
