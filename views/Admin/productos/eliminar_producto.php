<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";

$conn = Database::Conectar();

$id = intval($_GET["id"]);

$stmt = $conn->prepare(
    "DELETE FROM productos WHERE id_productos = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: inventario.php");
exit();
?>