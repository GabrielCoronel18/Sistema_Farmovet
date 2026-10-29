<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class RazasModel extends ConexionBD {

    public function obtenerRazasActivas(): array {
        $sql = "SELECT id_raza, nombre_raza
                FROM raza
                WHERE estado = 1
                ORDER BY nombre_raza";

        return $this->getConexion()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar una nueva raza
    public function registrar($nombre_raza, $id_especie) {
        $sql = "INSERT INTO raza (nombre_raza, id_especie, estado) VALUES (:nombre_raza, :id_especie, :estado)";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre_raza', $nombre_raza);
        $stmt->bindParam(':id_especie', $id_especie, PDO::PARAM_INT);
        $stmt->bindValue(':estado', 1);

        return $stmt->execute();
    }

    // Filtrar/Buscar razas por parámetro (nombre de raza o nombre de especie)
    public function filtrarRaza($param, int $pagina, int $limitacion) {
        $limite = $pagina * $limitacion;
        $offset = $limite - $limitacion;
        $busqueda = $param . "%";
        $conex = $this->getConexion();

        $sql = "SELECT r.*, e.nombre_especie 
                FROM raza r
                LEFT JOIN especie e ON r.id_especie = e.id_especie
                WHERE r.estado = 1 
                  AND (r.nombre_raza LIKE :param OR e.nombre_especie LIKE :param)
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":param", $busqueda);
        $query->bindParam(":limitacion", $limitacion, PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, PDO::PARAM_INT);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener listado de razas paginado
    public function obtenerRaza(int $pagina, int $limitacion) {
        $limite = $pagina * $limitacion;
        $offset = $limite - $limitacion;

        $conex = $this->getConexion();
        $sql = "SELECT r.*, e.nombre_especie 
                FROM raza r
                LEFT JOIN especie e ON r.id_especie = e.id_especie
                WHERE r.estado = 1 
                LIMIT :limitacion OFFSET :offset";

        $query = $conex->prepare($sql);
        $query->bindParam(":limitacion", $limitacion, PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Validar si la raza ya existe para una misma especie
    public function validarRaza($nombre_raza, $id_especie) {
        $sql = "SELECT * FROM raza WHERE nombre_raza = :nombre_raza AND id_especie = :id_especie AND estado = 1";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre_raza', $nombre_raza);
        $stmt->bindParam(':id_especie', $id_especie, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar lógicamente una raza (estado = 0)
    public function eliminarRaza($id_raza) {
        $sql = "UPDATE raza SET estado = 0 WHERE id_raza = :id_raza";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':id_raza', $id_raza, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Modificar los datos de una raza
    public function modificarRaza($id_raza, $nombre_raza, $id_especie) {
        $sql = "UPDATE raza SET nombre_raza = :nombre_raza, id_especie = :id_especie WHERE id_raza = :id_raza";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre_raza', $nombre_raza);
        $stmt->bindParam(':id_especie', $id_especie, PDO::PARAM_INT);
        $stmt->bindParam(':id_raza', $id_raza, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Obtener una sola raza por su id_raza
    public function obtenerRazaPorId(int $id_raza) {
        $conex = $this->getConexion();
        $sql = "SELECT r.*, e.nombre_especie 
                FROM raza r
                LEFT JOIN especie e ON r.id_especie = e.id_especie
                WHERE r.id_raza = :id_raza AND r.estado = 1";

        $query = $conex->prepare($sql);
        $query->bindParam(":id_raza", $id_raza, PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}