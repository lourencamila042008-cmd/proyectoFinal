<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: iniciogarantias.php");
    exit;
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM garantias WHERE id_garantia = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

echo "<script>alert('Garantía eliminada correctamente'); window.location='iniciogarantias.php';</script>";
?>