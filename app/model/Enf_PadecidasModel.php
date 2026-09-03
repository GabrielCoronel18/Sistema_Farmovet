<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class Enf_PadecidasModel extends ConexionBD {
      private int $id;
      private int $mascota;
      private int $patologia;
      private string $fecha_diagnostico;
      private string $estado_enfermedad;

      
      public function agregarEnfermedadPadecida(int $mascota, int $patologia, string $fecha_diagnostico, string $estado_enfermedad){
         $this->mascota = $mascota;
         $this->patologia = $patologia;
         $this->fecha_diagnostico = $fecha_diagnostico;
         $this->estado_enfermedad = $estado_enfermedad;
         
         $conex = $this->getConexion();
         $sql = "INSERT INTO enfermedades_padecidas(id_mascota,id_patologia,fecha_diagnostico,estado_enfermedad) 
                 VALUES(:mascota,:patologia, :fecha_diagnostico, :estado_enfermedad)";
         
                $query = $conex->prepare($sql);
                
                $query->bindParam(":mascota", $this->mascota);
                $query->bindParam(":patologia", $this->patologia);
                $query->bindParam(":fecha_diagnostico", $this->fecha_diagnostico);
                $query->bindParam(":estado_enfermedad", $this->estado_enfermedad);
         
         return $query->execute();
          
      }


      public function obtenerEnfermedadesPadecidas(int $id){
         $this->id = $id;
         $conex = $this->getConexion();
         $sql = "SELECT enfermedades_padecidas.*, patologia.nombre FROM enfermedades_padecidas
                INNER JOIN patologia ON enfermedades_padecidas.id_patologia = patologia.id_patologia 
                WHERE id_mascota = :id";
         
         $query = $conex->prepare($sql);
         $query->bindParam(":id", $this->id);

         $query->execute();
             
        return $query->fetchAll(\PDO::FETCH_ASSOC);
      }


        public function obtenerPatologiasActivas(){
         $conex = $this->getConexion();
         $query = $conex->query("SELECT id_patologia, nombre FROM patologia WHERE estado = 1 ORDER BY nombre");
         return $query->fetchAll(\PDO::FETCH_ASSOC);
      }

      public function eliminarEnfermedadesDeMascota(int $mascota){
         $conex = $this->getConexion();
         $query = $conex->prepare("DELETE FROM enfermedades_padecidas WHERE id_mascota = :mascota");
         $query->bindParam(":mascota", $mascota);
         return $query->execute();
      }

      public function EliminarEnfermedadPadecida(int $id){
        $this->id = $id;
        $conex = $this->getConexion();
         $sql = "DELETE FROM enfermedades_padecidas WHERE id_enfermedad_padecida = :id";
        
         $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);
        
        
        return $query->execute();

      }
}