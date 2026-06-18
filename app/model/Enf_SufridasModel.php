<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class Enf_SufridasModel extends ConexionBD {
      private int $id;
      private int $mascota;
      private int $patologia;
      private string $fecha_diagnostico;
      private string $estado_enfermedad;

      
      public function agregarEnfermedadSufrida(int $mascota, int $patologia, string $fecha_diagnostico, string $estado_enfermedad){
         $this->mascota = $mascota;
         $this->patologia = $patologia;
         $this->fecha_diagnostico = $fecha_diagnostico;
         $this->estado_enfermedad = $estado_enfermedad;
         
         $conex = $this->getConexion();
         $sql = "INSERT INTO enfermedades_sufridas(id_mascota,id_patologia,fecha_diagnostico,estado_enfermedad) 
                 VALUES(:mascota,:patologia, :fecha_diagnostico, :estado_enfermedad)";
         
                $query = $conex->prepare($sql);
                
                $query->bindParam(":mascota", $this->mascota);
                $query->bindParam(":patologia", $this->patologia);
                $query->bindParam(":fecha_diagnostico", $this->fecha_diagnostico);
                $query->bindParam(":estado_enfermedad", $this->estado_enfermedad);
         
         return $query->execute();
          
      }


      public function obtenerEnfermedadesSufridas(int $id){
         $this->id = $id;
         $conex = $this->getConexion();
         $sql = "SELECT enfermedades_sufridas.*, patologia.nombre FROM enfermedades_sufridas
                INNER JOIN patologia ON enfermedades_sufridas.id_patologia = patologia.id_patologia 
                WHERE id_mascota = :id";
         
         $query = $conex->prepare($sql);
         $query->bindParam(":id", $this->id);

         $query->execute();
             
        return $query->fetchAll(\PDO::FETCH_ASSOC);
      }


      public function EliminarEnfermedadSufrida(int $id){
        $this->id = $id;
        $conex = $this->getConexion();
         $sql = "DELETE FROM enfermedades_sufridas WHERE id_enfermedad_sufrida = :id";
        
         $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);
        
        
        return $query->execute();

      }
      
}