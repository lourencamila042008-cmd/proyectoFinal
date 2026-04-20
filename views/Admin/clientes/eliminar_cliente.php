<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

// 1️⃣ Verificar que llegue el ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: clientes.php");
    exit;
}
$id = intval($_GET['id']);

// 2️⃣ Verificar que el cliente exista
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id_clientes = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$cliente) {
    header("Location: clientes.php");
    exit;
}

// 3️⃣ Eliminar
$stmt = $conn->prepare("DELETE FROM clientes WHERE id_clientes = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    echo "<script>alert('Cliente eliminado correctamente'); window.location='clientes.php';</script>";
    exit;
} else {
    echo "<script>alert('Error al eliminar: " . $conn->error . "'); window.location='clientes.php';</script>";
    exit;
}
?>