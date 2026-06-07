<?php

// Cargo la conexión a la base de datos
require_once __DIR__ . "/../config/db.php";

// Clase para manejar todo lo de los clientes
class Cliente{

    // Variable para guardar la conexión
    private $conn;

    // Al iniciar, conecta a la base de datos
    public function __construct(){
        $this->conn = Database::Conectar();
    }

    // 🔹 OBTENER TODOS LOS CLIENTES
    // Los trae todos ordenados del más nuevo al más viejo
    public function obtenerTodos(){
        return $this->conn->query("SELECT * FROM clientes ORDER BY id_clientes DESC");
    }

    // 🔹 BUSCAR UN SOLO CLIENTE POR SU ID
    public function obtenerPorId($id){
        return $this->conn->query("SELECT * FROM clientes WHERE id_clientes=$id")->fetch_assoc();
    }

    // 🔹 CREAR NUEVO CLIENTE
    // Guarda nombre, cédula y teléfono de uno nuevo
    public function crear($nombre, $cedula, $telefono){
    $sql = "INSERT INTO clientes (nombre, cedula, telefono)
            VALUES (?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$nombre, $cedula, $telefono]);

    }

    // 🔹 ACTUALIZAR DATOS DE UN CLIENTE
    // Modifica nombre, teléfono y correo según el ID
    public function actualizar($id, $nombre, $telefono, $correo){
        return $this->conn->query("
            UPDATE clientes SET
            nombre='$nombre',
            telefono='$telefono',
            correo='$correo'
            WHERE id_clientes=$id
        ");
    }

    // 🔹 ELIMINAR CLIENTE
    // Borra el registro de la base según su ID
    public function eliminar($id){
        return $this->conn->query("
            DELETE FROM clientes WHERE id_clientes=$id
        ");
    }
}