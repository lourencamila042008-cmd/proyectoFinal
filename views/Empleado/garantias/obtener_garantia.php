<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$id_factura = intval($_GET['id_factura'] ?? 0);

$sql = "SELECT 
            p.id_productos,
            p.nombre,
            d.cantidad,
            d.precio,
            d.subtotal,
            f.fecha
        FROM detallefactura d
        JOIN productos p ON d.id_productos = p.id_productos
        JOIN facturas f ON d.id_facturas = f.id_facturas
        WHERE d.id_facturas = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_factura);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    // calcular si está vencido
    $fecha_factura = new DateTime($row['fecha']);
    $hoy = new DateTime();
    $dias = $fecha_factura->diff($hoy)->days;

    $row['dias'] = $dias;
    $row['fuera_tiempo'] = $dias > 30; // 🔥 REGLA

    $data[] = $row;
}

echo json_encode($data);