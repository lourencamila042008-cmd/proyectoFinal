<?php

require_once __DIR__."/../config/db.php";

class usuarios{
    private $db;
    public function __construct(){
        $this->db=Database::conectar();
    }

    public function mostrar(){
        $sql="SELECT* FROM usuarios";

     $resul=$this->db->query($sql);


     return $resul->fetch_all(MYSQLI_ASSOC);

    }

      public function save($usuario,$contraseña,$id_rol,$estado,$fecha_creacion){
         $sql="INSERT INTO usuarios (usuario,contraseña,id_rol,estado,fecha_creacion) VALUES('$usuario','$contraseña','$id_rol','$estado','$fecha_creacion')";

       return $this->db->query($sql);
    
      }

         public function GetById($id){
        $sql="SELECT* FROM usuarios WHERE id_usuario=$id";
        
        $resul=$this->db->query($sql);

        return $resul->fetch_assoc();

   }

   public function update($id,$usuario,$contraseña,$id_rol,$estado,$fecha_creacion){
    $sql="UPDATE usuarios SET usuario='$usuario',contraseña='$contraseña',id_rol='$id_rol',estado='$estado',fecha_creacion='$fecha_creacion'WHERE id_usuario=$id";

    return $this->db->query($sql);

   }

   public function DELETE($id){
    $sql="DELETE FROM usuarios WHERE id_usuario=$id";

    return $this->db->query($sql);



   }
       
     


    
}

  