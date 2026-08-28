<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class PatologiaModel extends ConexionBD {
    private int $id;
    private string $nombre;
    private string $tipo;
    private string $sintomas;

    public function agregarPatologia(string $nombre, string $tipo, string $sintomas) {
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->sintomas = $sintomas;

        $conex = $this->getConexion();
        $sql = "INSERT INTO patologia (nombre, tipo, sintomas, estado) 
                VALUES (:nombre, :tipo, :sintomas, 1)";

        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $this->nombre);
        $query->bindParam(":tipo", $this->tipo);
        $query->bindParam(":sintomas", $this->sintomas);

        return $query->execute();
    }

    public function obtenerPatologias(int $pagina, int $limitacion) {
        $limite = $pagina * $limitacion;
        $offset = $limite - $limitacion;

        $conex = $this->getConexion();
        $sql = "SELECT * FROM patologia WHERE estado = 1 
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public function obtenerPatologiaPorId(int $id) {
        $this->id = $id;

        $conex = $this->getConexion();
        $sql = "SELECT * FROM patologia WHERE id_patologia = :id AND estado = 1";

        $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);
        $query->execute();

        return $query->fetch();
    }

    public function filtrarPatologia($param, int $pagina, int $limitacion) {
        $limite = $pagina * $limitacion;
        $offset = $limite - $limitacion;
        $busqueda = $param . "%";

        $conex = $this->getConexion();
        $sql = "SELECT * FROM patologia 
                WHERE estado = 1 
                  AND (id_patologia LIKE :param
                    OR nombre LIKE :param
                    OR tipo LIKE :param
                    OR sintomas LIKE :param)
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":param", $busqueda);
        $query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public function actualizarPatologia(int $id, string $nombre, string $tipo, string $sintomas) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->sintomas = $sintomas;

        $conex = $this->getConexion();
        $sql = "UPDATE patologia 
                SET nombre = :nombre, 
                    tipo = :tipo, 
                    sintomas = :sintomas 
                WHERE id_patologia = :id";

        $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);
        $query->bindParam(":nombre", $this->nombre);
        $query->bindParam(":tipo", $this->tipo);
        $query->bindParam(":sintomas", $this->sintomas);

        return $query->execute();
    }

    public function eliminarPatologia(int $id) {
        $this->id = $id;

        $conex = $this->getConexion();
        $sql = "UPDATE patologia SET estado = 0 WHERE id_patologia = :id";

        $query = $conex->prepare($sql);
        $query->bindParam(":id", $this->id);

        return $query->execute();
    }
}
?>