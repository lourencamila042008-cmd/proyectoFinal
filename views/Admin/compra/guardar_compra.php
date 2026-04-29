<?php
require_once "../../../config/db.php";

$conn = Database::Conectar();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id_proveedor = intval($_POST['id_proveedor']);
    $id_producto  = intval($_POST['id_producto']);

    $cantidad = intval($_POST['cantidad']);
    $precio   = floatval($_POST['precio']);

    $total = $cantidad * $precio;

    $fecha = date("Y-m-d");

    // 🔥 TRANSACCIÓN
    $conn->begin_transaction();

    try{

        // 1️⃣ GUARDAR COMPRA
        $stmt = $conn->prepare("
        INSERT INTO compras
        (id_proveedor, precio_total, fecha)
        VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "ids",
            $id_proveedor,
            $total,
            $fecha
        );

        $stmt->execute();

        $id_compra = $conn->insert_id;

        $stmt->close();

        // 2️⃣ SUMAR STOCK
        $stmt = $conn->prepare("
        UPDATE productos
        SET stock = stock + ?
        WHERE id_productos = ?
        ");

        $stmt->bind_param(
            "ii",
            $cantidad,
            $id_producto
        );

        $stmt->execute();

        $stmt->close();

        // ✅ CONFIRMAR
        $conn->commit();

        header("Location: compras.php");

    } catch(Exception $e){

        $conn->rollback();

        echo "Error: " . $e->getMessage();
    }
}
?>