<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class PlanSanitarioModel extends ConexionBD
{
    private string $nombre;
    private string $descripcion;
    private int $estado;

    public function agregarPlan(string $nombre, string $descripcion, string $fecha_inicio): bool
    {
        $conex = $this->getConexion();
        $sql = "INSERT INTO plan_sanitario (nombre_plan, descripcion, fecha_inicio, estado) VALUES (:nombre, :descripcion, :fecha, 1)";
        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $nombre);
        $query->bindParam(":descripcion", $descripcion);
        $query->bindParam(":fecha", $fecha_inicio);

        return $query->execute();
    }

    public function obtenerPlanes(int $pagina = 1, int $limite = 10): array
    {
        $offset = ($pagina - 1) * $limite;
        $conex = $this->getConexion();
        $sql = "SELECT id_plan, nombre_plan, descripcion, fecha_inicio, estado 
                FROM plan_sanitario 
                WHERE estado = 1 
                ORDER BY id_plan DESC 
                LIMIT :limite OFFSET :offset";
        $query = $conex->prepare($sql);
        $query->bindParam(":limite", $limite, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }

    public function obtenerPlanPorId(int $id): ?array
    {
        $conex = $this->getConexion();
        $sql = "SELECT id_plan, nombre_plan, descripcion, fecha_inicio, estado FROM plan_sanitario WHERE id_plan = :id AND estado = 1";
        $query = $conex->prepare($sql);
        $query->bindParam(":id", $id);
        $query->execute();
        $result = $query->fetch();
        return $result ?: null;
    }

    public function filtrarPlanes(string $busqueda, int $pagina = 1, int $limite = 10): array
    {
        $offset = ($pagina - 1) * $limite;
        $param = "%" . $busqueda . "%";
        $conex = $this->getConexion();
        $sql = "SELECT id_plan, nombre_plan, descripcion, fecha_inicio, estado 
                FROM plan_sanitario 
                WHERE estado = 1 AND (nombre_plan LIKE :param OR descripcion LIKE :param) 
                ORDER BY id_plan DESC 
                LIMIT :limite OFFSET :offset";
        $query = $conex->prepare($sql);
        $query->bindParam(":param", $param);
        $query->bindParam(":limite", $limite, \PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }

    public function actualizarPlan(int $id, string $nombre, string $descripcion, string $fecha_inicio): bool
    {
        $conex = $this->getConexion();
        $sql = "UPDATE plan_sanitario SET nombre_plan = :nombre, descripcion = :descripcion, fecha_inicio = :fecha WHERE id_plan = :id";
        $query = $conex->prepare($sql);
        $query->bindParam(":nombre", $nombre);
        $query->bindParam(":descripcion", $descripcion);
        $query->bindParam(":fecha", $fecha_inicio);
        $query->bindParam(":id", $id);
        return $query->execute();
    }

    public function eliminarPlan(int $id): bool
    {
        $conex = $this->getConexion();
        $sql = "UPDATE plan_sanitario SET estado = 0 WHERE id_plan = :id";
        $query = $conex->prepare($sql);
        $query->bindParam(":id", $id);
        return $query->execute();
    }
}