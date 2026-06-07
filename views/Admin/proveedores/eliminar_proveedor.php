<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: proveedores.php");
    exit;
}

$id = intval($_GET['id']);

/* =========================
   VALIDAR SI EXISTE
========================= */
$stmt = $conn->prepare("SELECT * FROM proveedores WHERE id_proveedores = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$proveedor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$proveedor) {
    header("Location: proveedores.php");
    exit;
}

/* =========================
   ELIMINAR
========================= */
$stmt = $conn->prepare("DELETE FROM proveedores WHERE id_proveedores = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: proveedores.php?msg=eliminado");
    exit;
} else {
    $stmt->close();
    echo "<script>alert('Error al eliminar proveedor'); window.location='proveedores.php';</script>";
}
?>