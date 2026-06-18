<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class Cirgs_PreviasModel extends ConexionBD {
      private int $id;
      private int $mascota;
      private string $nombre;
      private string $fecha;

      
      public function agregarCirugiaPrevia(int $mascota, string $nombre, string $fecha){
         $this->mascota = $mascota;
         $this->nombre = $nombre;
         $this->fecha = $fecha;
         
         $conex = $this->getConexion();
         $sql = "INSERT INTO cirugia_previa(id_mascota,nombre_cirugia,fecha_cirugia) 
                 VALUES(:mascota, :nombre, :fecha)";
         
                $query = $conex->prepare($sql);
                
                $query->bindParam(":mascota", $this->mascota);
                $query->bindParam(":nombre", $this->nombre);
                $query->bindParam(":fecha", $this->fecha);
         
         return $query->execute();
          
      }


      public function obtenerCirugiasPrevias(int $id){
         $this->id = $id;
         $conex = $this->getConexion();
         $sql = "SELECT * FROM cirugia_previa WHERE id_mascota = :id";
         
         $query = $conex->prepare($sql);
         $query->bindParam(":id", $this->id);

         $query->execute();
             
        return $query->fetchAll(\PDO::FETCH_ASSOC);
      }


      public function EliminarCirugiaPrevia(int $id){
        $this->id = $id;
        $conex = $this->getConexion();
         $sql = "DELETE FROM cirugia_previa WHERE id_cirugia_previa = :id";
        
         $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);
        
        
        return $query->execute();

      }
      
}