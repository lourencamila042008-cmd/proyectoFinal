<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: usuarios.php");
    exit;
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

echo "<script>alert('Usuario eliminado correctamente'); window.location='usuarios.php';</script>";
?>