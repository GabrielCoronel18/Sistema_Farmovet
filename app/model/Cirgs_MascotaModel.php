<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class Cirgs_MascotaModel extends ConexionBD {
      private int $id;
      private int $mascota;
   private int $cirugia;
      private string $fecha;

      
   public function agregarCirugiaPrevia(int $mascota, int $cirugia, string $fecha){
         $this->mascota = $mascota;
      $this->cirugia = $cirugia;
         $this->fecha = $fecha;
         
         $conex = $this->getConexion();
      $sql = "INSERT INTO cirugia_mascota(id_mascota,id_cirugia,fecha_cirugia) 
           VALUES(:mascota, :cirugia, :fecha)";
         
                $query = $conex->prepare($sql);
                
                $query->bindParam(":mascota", $this->mascota);
                $query->bindParam(":cirugia", $this->cirugia);
                $query->bindParam(":fecha", $this->fecha);
         
         return $query->execute();
          
      }
  public function eliminarCirugiasDeMascota(int $mascota){
         $conex = $this->getConexion();
         $query = $conex->prepare("DELETE FROM cirugia_mascota WHERE id_mascota = :mascota");
         $query->bindParam(":mascota", $mascota);
         return $query->execute();
      }

      public function obtenerCirugiasDisponibles(){
         $conex = $this->getConexion();
         $query = $conex->query("SELECT id_cirugia, nombre_cirugia FROM cirugia ORDER BY nombre_cirugia");
         return $query->fetchAll(\PDO::FETCH_ASSOC);
      }

      public function obtenerCirugiasPrevias(int $id){
         $this->id = $id;
         $conex = $this->getConexion();
         $sql = "SELECT cirugia_mascota.*, cirugia.nombre_cirugia, cirugia.gravedad
                 FROM cirugia_mascota
                 INNER JOIN cirugia ON cirugia_mascota.id_cirugia = cirugia.id_cirugia
                 WHERE cirugia_mascota.id_mascota = :id";
         
         $query = $conex->prepare($sql);
         $query->bindParam(":id", $this->id);

         $query->execute();
             
        return $query->fetchAll(\PDO::FETCH_ASSOC);
      }


      public function EliminarCirugiaPrevia(int $id){
        $this->id = $id;
        $conex = $this->getConexion();
             $sql = "DELETE FROM cirugia_mascota WHERE id_cirugia_mascota = :id";
        
         $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);
        
        
        return $query->execute();

      }
      
}