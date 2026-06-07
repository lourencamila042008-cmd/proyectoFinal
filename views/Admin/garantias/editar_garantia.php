<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM garantias WHERE id_garantia=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$g = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$g) {
    header("Location: garantias.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $motivo = trim($_POST['motivo']);
    $solucion = trim($_POST['solucion']);
    $estado = $_POST['estado'];

    $permitidos = ['pendiente', 'en_revision', 'resuelto'];

    if (!in_array($estado, $permitidos)) {
        die("Estado inválido");
    }

    /* =========================
       ESTADO ANTERIOR
    ========================= */
    $q = $conn->prepare("SELECT estado FROM garantias WHERE id_garantia=?");
    $q->bind_param("i", $id);
    $q->execute();
    $actual = $q->get_result()->fetch_assoc();
    $estado_anterior = $actual['estado'];
    $q->close();

    /* =========================
       UPDATE GARANTÍA
    ========================= */
    $stmt = $conn->prepare("
        UPDATE garantias
        SET motivo=?, solucion=?, estado=?
        WHERE id_garantia=?
    ");

    $stmt->bind_param("sssi", $motivo, $solucion, $estado, $id);
    $stmt->execute();
    $stmt->close();

    /* =========================
       INSERT HISTORIAL
    ========================= */
    if ($estado_anterior != $estado) {

        $hist = $conn->prepare("
            INSERT INTO historial_garantias
            (id_garantia, estado_anterior, estado_nuevo)
            VALUES (?, ?, ?)
        ");

        $hist->bind_param("iss", $id, $estado_anterior, $estado);
        $hist->execute();
        $hist->close();
    }

    header("Location: garantias.php?msg=editado");
    exit;
}
?>
<style>
    form{
    max-width:500px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:18px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
}

input, select{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:12px;
    border:1px solid #dbe2ea;
    outline:none;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:#1e3a5f;
    color:white;
    font-weight:600;
    cursor:pointer;
}

button:hover{
    background:#16304d;
}
</style>
<form method="POST">
    <input name="motivo" value="<?= $g['motivo'] ?>">
    <input name="solucion" value="<?= $g['solucion'] ?>">

    <select name="estado">
        <option value="pendiente" <?= $g['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
        <option value="en_revision" <?= $g['estado']=='en_revision'?'selected':'' ?>>En revisión</option>
        <option value="resuelto" <?= $g['estado']=='resuelto'?'selected':'' ?>>Resuelto</option>
    </select>

    <button>Guardar</button>
</form>