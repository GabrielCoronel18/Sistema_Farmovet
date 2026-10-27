<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;

class TipoMedicamentoModel extends ConexionBD {
    private int $id;
    private string $nombre;
    private int $estado;

   
   public function agregarTipoMedicamento(string $nombre, int $estado = 1) {
        $this->nombre = $nombre;

        $conex = $this->getConexion();
        $sql = "INSERT INTO tipo_medicamento (nom_tipo_medicamento, estado)
                VALUES (:nombre, :estado)";

        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $this->nombre);
        $query->bindParam(":estado", $estado, \PDO::PARAM_INT);

        return $query->execute();
    }
   
    public function obtenerTipoMedicamento(int $pagina, int $limitacion) {
        $offset = ($pagina * $limitacion) - $limitacion;
        $conex = $this->getConexion();
        $sql = "SELECT * FROM tipo_medicamento LIMIT :limitacion OFFSET :offset";
        $query = $conex->prepare($sql);
        $query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerTipoMedicamentoPorId(int $id) {
        $this->id = $id;
        $conex = $this->getConexion();
        $query = $conex->prepare("SELECT * FROM tipo_medicamento WHERE id_tipo_medicamento = :id ");
        $query->bindParam(":id", $this->id, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function filtrarTipoMedicamento(int $pagina, int $limitacion, string $param) {
        $busqueda = $param . "%";
        $offset = ($pagina * $limitacion) - $limitacion;
        $conex = $this->getConexion();
        $sql = "SELECT * FROM tipo_medicamento
                WHERE estado = 1 AND nom_tipo_medicamento LIKE :busqueda
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":busqueda", $busqueda, \PDO::PARAM_STR);
        $query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function actualizarTipoMedicamento(int $id, string $nombre, int $estado) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->estado = $estado;

        $conex = $this->getConexion();
        $sql = "UPDATE tipo_medicamento SET nom_tipo_medicamento = :nombre, estado = :estado WHERE id_tipo_medicamento = :id";

        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $this->nombre);
        $query->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $query->bindParam(":id", $this->id, \PDO::PARAM_INT);

        return $query->execute();
    }
    public function eliminarTipoMedicamento(int $id) {
        $this->id = $id;

        $conex = $this->getConexion();
        $sql = "UPDATE tipo_medicamento SET estado = 0 WHERE id_tipo_medicamento = :id";

        $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id, \PDO::PARAM_INT);

        return $query->execute();
    }
}