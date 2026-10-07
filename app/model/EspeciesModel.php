<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class EspeciesModel extends ConexionBD {

    public function registrar($nombre) {
        $sql = "INSERT INTO especie (nombre_especie, estado) VALUES (:nombre, 1)";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);

        return $stmt->execute();
    }

    public function filtrarEspecie($param, int $pagina, int $limitacion) {
        $limite = $pagina * $limitacion;
        $offset = $limite - $limitacion;
        $busqueda = $param . "%";
        $conex = $this->getConexion();
        $sql = "SELECT * FROM especie 
                WHERE estado = 1 
                  AND (nombre_especie LIKE :param)
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":param", $busqueda);
        $query->bindParam(":limitacion", $limitacion, PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, PDO::PARAM_INT);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEspecie(int $pagina, int $limitacion) {
        $limite = $pagina * $limitacion;
        $offset = $limite - $limitacion;

        $conex = $this->getConexion();
        $sql = "SELECT * FROM especie 
                WHERE estado = 1 LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":limitacion", $limitacion, PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validarEspecies($nombre) {
        $sql = "SELECT * FROM especie WHERE nombre_especie = :nombre AND estado = 1";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarEspecie($id) {
        $sql = "UPDATE especie SET estado = 0 WHERE id_especie = :id";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function modificarEspecie($id, $nombre) {
        $sql = "UPDATE especie SET nombre_especie = :nombre WHERE id_especie = :id";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function obtenerEspeciePorId(int $id) {
        $conex = $this->getConexion();
        $sql = "SELECT * FROM especie WHERE id_especie = :id AND estado = 1";

        $query = $conex->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}