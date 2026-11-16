<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use Gabriel\SistemaFarmovet\helpers\traits\Validation;
use Gabriel\SistemaFarmovet\helpers\traits\AlergiasValidation;

use PDO;

class AlergiaModel extends ConexionBD {
      private int $id;
      private string $nombre;
      private string $tipo;
      private int $estado; 
      
    use Validation;
    use AlergiasValidation;
    /**
     * RF 5.6.1 Registrar Alergia
     * Registra una nueva alergia con estado activo (1).
     */
    public function agregarAlergia($nombre, $tipo) {

    $this->nombre = $nombre;
    $this->tipo = $tipo;
    $this->estado = 1;

            $db = $this->getConexion(); 
            $sql = "INSERT INTO alergia (nombre_alergia, tipo, estado) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $this->nombre,
                $this->tipo,
                $this->estado
            ]);
        
    }

    /**
     * RF 5.6.2 Consultar Alergia
     * Obtiene todas las alergias activas (estado = 1).
     */
    public function obtenerAlergias() {
        
            $db = $this->getConexion();
            $sql = "SELECT *
                    FROM alergia 
                    WHERE estado = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

    /**
     * Consultar una alergia específica por su ID.
     */
    public function obtenerAlergiaPorId($id) {
        $this->id = $id;
            $db = $this->getConexion();
            $sql = "SELECT id_alergia, nombre_alergia, tipo, estado 
                    FROM alergia 
                    WHERE id_alergia = ? AND estado = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
      
    }

    /**
     * RF 5.6.2.1 Actualizar Alergia
     * Modifica los datos de una alergia existente.
     */
    public function actualizarAlergia($id,$nombre,$tipo) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;

            $db = $this->getConexion();
            $sql = "UPDATE alergia SET nombre_alergia = ?, tipo = ? WHERE id_alergia = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $this->nombre,
                $this->tipo,
                $this->id
            ]);
       
    }

    /**
     * RF 5.6.2.2 Eliminar Alergia
     * Realiza un borrado lógico cambiando el estado a 0.
     */
    public function eliminarAlergia($id) {
        $this->id = $id;

            $db = $this->getConexion();
            $sql = "UPDATE alergia SET estado = 0 WHERE id_alergia = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$this->id]);
      
    }
}