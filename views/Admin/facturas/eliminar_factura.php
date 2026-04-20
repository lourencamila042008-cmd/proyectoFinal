<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: facturas.php");
    exit;
}
$id = intval($_GET['id']);

// Verificar que la factura exista
$stmt = $conn->prepare("SELECT * FROM facturas WHERE id_facturas = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$factura = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$factura) {
    header("Location: facturas.php");
    exit;
}

// Eliminar la factura (el detalle se elimina solo por CASCADE)
$stmt = $conn->prepare("DELETE FROM facturas WHERE id_facturas = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

echo "<script>alert('Factura eliminada correctamente'); window.location='facturas.php';</script>";
?>