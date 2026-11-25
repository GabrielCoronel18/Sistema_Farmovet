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
   public function agregarAlergia($nombre, $tipo, int $estado = 1) {
    $this->nombre = $nombre;
    $this->tipo = $tipo;
    $this->estado = $estado;

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
    public function obtenerAlergias(int $pagina, int $limitacion) {
    $offset = ($pagina * $limitacion) - $limitacion;
    
    $db = $this->getConexion();
    $sql = "SELECT id_alergia, nombre_alergia, tipo, estado
            FROM alergia
            ORDER BY id_alergia ASC
            LIMIT :limitacion OFFSET :offset";
    $stmt = $db->prepare($sql);
    
    $stmt->bindValue(":limitacion", $limitacion, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function filtrarAlergias(string $param, int $pagina, int $limitacion){
        $offset = ($pagina * $limitacion) - $limitacion;
		$busqueda = $param . "%";

            $db = $this->getConexion();
            $sql = "SELECT *
                    FROM alergia 
                    WHERE (
				   CAST(id_alergia AS CHAR) LIKE :param_id
				   OR nombre_alergia LIKE :param_nombre
				   OR tipo LIKE :param_tipo
				)
				ORDER BY id_alergia
				LIMIT :limitacion OFFSET :offset";
            

        $query = $db->prepare($sql);
		$query->bindParam(":param_id", $busqueda);
		$query->bindParam(":param_nombre", $busqueda);
		$query->bindParam(":param_tipo", $busqueda);
		$query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
		$query->bindParam(":offset", $offset, \PDO::PARAM_INT);
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_ASSOC);

    }
    public function contarAlergias(?string $param = null): int 
    {
        $conex = $this->getConexion();
		if ($param === null) {
			$query = $conex->query("SELECT COUNT(*) FROM alergia");
		} else {
			$query = $conex->prepare("SELECT COUNT(*) FROM alergia
				WHERE (
					CAST(id_alergia AS CHAR) LIKE :param_id
					OR nombre_alergia LIKE :param_nombre
					OR tipo LIKE :param_tipo
				)");
			$busqueda = $param . "%";
			$query->bindValue(":param_id", $busqueda);
			$query->bindValue(":param_nombre", $busqueda);
			$query->bindValue(":param_tipo", $busqueda);
			$query->execute();
		}

		return (int) $query->fetchColumn();
    }
    /**
     * Consultar una alergia específica por su ID.
     */
    public function obtenerAlergiaPorId($id) {
        $this->id = $id;
            $db = $this->getConexion();
            $sql = "SELECT id_alergia, nombre_alergia, tipo, estado 
                    FROM alergia 
                    WHERE id_alergia =:id ";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
      
    }

    /**
     * RF 5.6.2.1 Actualizar Alergia
     * Modifica los datos de una alergia existente.
     */
    public function actualizarAlergia($id, $nombre, $tipo, int $estado) {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->tipo = $tipo;
    $this->estado = $estado;

    $db = $this->getConexion();
    $sql = "UPDATE alergia SET nombre_alergia = ?, tipo = ?, estado = ? WHERE id_alergia = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        $this->nombre,
        $this->tipo,
        $this->estado,
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