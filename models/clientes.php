<?php

require_once __DIR__ . "/../config/db.php";

class Cliente{

    private $conn;

    public function __construct(){
        $this->conn = Database::Conectar();
    }

    // 🔹 LISTAR
    public function obtenerTodos(){
        return $this->conn->query("SELECT * FROM clientes ORDER BY id_clientes DESC");
    }

    // 🔹 OBTENER UNO
    public function obtenerPorId($id){
        return $this->conn->query("SELECT * FROM clientes WHERE id_clientes=$id")->fetch_assoc();
    }

    // 🔹 CREAR
    public function crear($nombre, $cedula, $telefono){
    $sql = "INSERT INTO clientes (nombre, cedula, telefono)
            VALUES (?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$nombre, $cedula, $telefono]);

    }

    // 🔹 ACTUALIZAR
    public function actualizar($id, $nombre, $telefono, $correo){
        return $this->conn->query("
            UPDATE clientes SET
            nombre='$nombre',
            telefono='$telefono',
            correo='$correo'
            WHERE id_clientes=$id
        ");
    }

    // 🔹 ELIMINAR
    public function eliminar($id){
        return $this->conn->query("
            DELETE FROM clientes WHERE id_clientes=$id
        ");
    }
}