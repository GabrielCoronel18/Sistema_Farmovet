<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class RolModel extends ConexionBD
{
    private string $nombre;
    private int $estado;

    public function agregarRol(string $nombre): bool
    {
        $this->nombre = $nombre;

        $conex = $this->getConexion();
        $sql = "INSERT INTO rol (nombre_rol, estado) VALUES (:nombre, 1)";
        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $this->nombre);

        return $query->execute();
    }

    public function obtenerRoles(int $pagina = 1, int $limite = 10): array
    {
        $offset = ($pagina - 1) * $limite;
        $conex = $this->getConexion();
        $sql = "SELECT id_rol, nombre_rol, estado 
                FROM rol 
                WHERE estado = 1 
                ORDER BY nombre_rol 
                LIMIT :limite OFFSET :offset";
        $query = $conex->prepare($sql);
        $query->bindParam(":limite", $limite, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }

    public function obtenerRolPorId(int $id): ?array
    {
        $conex = $this->getConexion();
        $sql = "SELECT id_rol, nombre_rol, estado FROM rol WHERE id_rol = :id AND estado = 1";
        $query = $conex->prepare($sql);
        $query->bindParam(":id", $id);
        $query->execute();
        $result = $query->fetch();
        return $result ?: null;
    }

    public function filtrarRoles(string $busqueda, int $pagina = 1, int $limite = 10): array
    {
        $offset = ($pagina - 1) * $limite;
        $param = "%" . $busqueda . "%";
        $conex = $this->getConexion();
        $sql = "SELECT id_rol, nombre_rol, estado 
                FROM rol 
                WHERE estado = 1 AND nombre_rol LIKE :param 
                ORDER BY nombre_rol 
                LIMIT :limite OFFSET :offset";
        $query = $conex->prepare($sql);
        $query->bindParam(":param", $param);
        $query->bindParam(":limite", $limite, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }

    public function actualizarRol(int $id, string $nombre): bool
    {
        $conex = $this->getConexion();
        $sql = "UPDATE rol SET nombre_rol = :nombre WHERE id_rol = :id";
        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $nombre);
        $query->bindParam(":id", $id);
        return $query->execute();
    }

    public function eliminarRol(int $id): bool
    {
        $conex = $this->getConexion();
        $sql = "UPDATE rol SET estado = 0 WHERE id_rol = :id";
        $query = $conex->prepare($sql);
        $query->bindParam(":id", $id);
        return $query->execute();
    }
}