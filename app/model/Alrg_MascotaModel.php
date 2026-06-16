<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class AlergiaMascota extends ConexionBD {
      private int $id;
      private int $alergia;
      private int $mascota;
      private string $fecha_deteccion;

      
      public function asociarAlergia(int $alergia,int $mascota, string $fecha_deteccion){
         $this->alergia = $alergia;
         $this->mascota = $mascota;
         $this->fecha_deteccion = $fecha_deteccion;
         
         $conex = $this->getConexion();
         $sql = "INSERT INTO alergia_mascota(id_alergia,id_mascota,fecha_deteccion) 
                 VALUES(:alergia, :mascota, :fecha_deteccion)";
         
                $query = $conex->prepare($sql);
                $query->bindParam(":alergia", $this->alergia);
                $query->bindParam(":mascota", $this->mascota);
                $query->bindParam(":fecha_deteccion", $this->fecha_deteccion);
         
         return $query->execute();
          
      }


      public function obtenerAlergiasAsociadas(){
        
        $conex = $this->getConexion();
         $sql = "SELECT * FROM alergia_mascota";
         $query = $conex->prepare($sql);

         $query->execute();
             
        return $query->fetchAll(\PDO::FETCH_ASSOC);
      }


      public function EliminarAlergiasAsociadas($id){
        $this->id = $id;
        $conex = $this->getConexion();
         $sql = "DELETE FROM alergia_mascota WHERE id_alergia_mascota = :id";
        
         $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);
        
        
        return $query->execute();

      }
      
}