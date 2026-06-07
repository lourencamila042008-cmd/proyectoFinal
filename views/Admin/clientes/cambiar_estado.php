<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $id = intval($_GET['id']);
    $nuevo_estado = ($_GET['estado'] == 'activo') ? 'activo' : 'inactivo';

    // ✅ CAMPO CORRECTO: "motivo"
    $stmt = $conn->prepare("UPDATE clientes SET motivo = ? WHERE id_clientes = ?");
    $stmt->bind_param("si", $nuevo_estado, $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: clientes.php");
exit;
?>