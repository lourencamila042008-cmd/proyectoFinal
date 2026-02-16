<?php

require_once __DIR__."/../config/db.php";

class Usuario {
    private $db;
    public function __construct(){
        $this->db = Database::Conectar();
    }
    public function mostrar(){
        #consulta para obtener toddos los ususario
        $sql = "SELECT * FROM usuario";
        #ejecutar la consulta
        $result = $this->db->query($sql);
        
          if(!$result){
        die("Error en consulta: " . $this->db->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}
    

    public function save($nombre_negocio, $nombre_usuario, $apellido_usuario, $telefono, $correo, $id_rol, $contraseña)
     {
        
        $sql = "INSERT INTO usuario (nombre_negocio, nombre_usuario, apellido_usuario, telefono, correo, id_rol, contraseña) 
        VALUES ('$nombre_negocio', '$nombre_usuario', '$apellido_usuario', '$telefono', '$correo', $id_rol, '$contraseña')";
        # se guardan los datos 
        return $this->db->query($sql);
     }

     public function GetById($id){
     #consulta para buscar  
     $sql = "SELECT * FROM usuario WHERE id_usuario = $id";
       
     $result = $this->db->query($sql);
     #envia y guarda valor buscado   
     return $result->fetch_assoc();
        
     }

     public function update($id_usuario,$nombre_negocio, $nombre_usuario, $apellido_usuario, $telefono, $correo, $id_rol, $contraseña){
     #consulta para actualizar   
     $sql = "UPDATE usuario SET nombre_negocio='$nombre_negocio', nombre_usuario='$nombre_usuario', apellido_usuario='$apellido_usuario', telefono='$telefono', correo='$correo', id_rol=$id_rol, contraseña='$contraseña' WHERE 
     id_usuario=$id_usuario";
     
        return $this->db->query($sql);
     }

     public function delete($id){
        $sql = "DELETE FROM usuario WHERE id_usuario = $id";
        return $this->db->query($sql);
     }
}
?>