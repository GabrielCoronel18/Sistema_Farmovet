<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use Gabriel\SistemaFarmovet\helpers\traits\Validation;

class PresentacionModel extends ConexionBD {
    private int $id;
    private string $nombre;
    private int $estado;

    use Validation;

    public function agregarPresentacion(string $nombre, int $estado = 1) {
        $this->nombre = $nombre;
        
      
        $conex = $this->getConexion();
        $sql = "INSERT INTO presentacion (nombre_presentacion, estado)
                VALUES (:nombre, :estado)";

        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $this->nombre);
        $query->bindParam(":estado", $estado, \PDO::PARAM_INT);

        return $query->execute();
    }

   public function obtenerPresentacion(int $pagina, int $limitacion) {
        $offset = ($pagina * $limitacion) - $limitacion;
        $conex = $this->getConexion();
        $sql = "SELECT * FROM presentacion
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerPresentacionPorId(int $id) {
        $this->id = $id;
        $conex = $this->getConexion();
        $query = $conex->prepare("SELECT * FROM presentacion WHERE id_presentacion = :id ");
        $query->bindParam(":id", $this->id, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function filtrarPresentacion(string $param, int $pagina, int $limitacion) {
        $offset = ($pagina * $limitacion) - $limitacion;
        $busqueda = $param . "%";
        $conex = $this->getConexion();
        $sql = "SELECT * FROM presentacion
                WHERE estado = 1 AND (nombre_presentacion LIKE :busqueda)
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":busqueda", $busqueda, \PDO::PARAM_STR);
        $query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function contarPresentaciones(?string $param = null): int {
        $conex = $this->getConexion();
        if ($param === null) {
            $query = $conex->query("SELECT COUNT(*) FROM presentacion");
        } else {
            $query = $conex->prepare("SELECT COUNT(*) FROM presentacion
                WHERE estado = 1 AND nombre_presentacion LIKE :busqueda");
            $query->bindValue(":busqueda", $param . "%");
            $query->execute();
        }

        return (int) $query->fetchColumn();
    }

    public function actualizarPresentacion(int $id, string $nombre, int $estado) {
        $this->id = $id;
        $this->nombre = $nombre;

        $conex = $this->getConexion();
        $sql = "UPDATE presentacion SET nombre_presentacion = :nombre, estado = :estado WHERE id_presentacion = :id";

        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $this->nombre);
        $query->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $query->bindParam(":id", $this->id, \PDO::PARAM_INT);

        return $query->execute();
    }

    public function eliminarPresentacion(int $id) {
        $this->id = $id;

        $conex = $this->getConexion();
        $sql = "UPDATE presentacion SET estado = 0 WHERE id_presentacion = :id";

        $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id, \PDO::PARAM_INT);

        return $query->execute();
    }
}