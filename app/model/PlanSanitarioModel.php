<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;
use Exception;

// Forzamos la inclusión de la conexión por si el autoload falla
require_once __DIR__ . '/../config/ConexionBD.php'; // linea de mas //

class PlanSanitarioModel extends ConexionBD {
    public function registrarPlan($datos) {
        try {
            $db = $this->getConexion(); 
            $sql = "INSERT INTO plan_sanitario (id_mascota, id_medicamento, fecha_aplicacion, proximo_refuerzo, estado) VALUES (?, ?, ?, ?, 1)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $datos['id_mascota'],
                $datos['id_medicamento'],
                $datos['fecha_aplicacion'],
                $datos['proximo_refuerzo']
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function consultarPlanes(?string $busqueda = null, int $start = 0, ?int $length = null): array {
        try {
            $db = $this->getConexion();
            $sql = "SELECT p.id_plan, p.id_mascota, m.nombre AS nombre_mascota, 
                           p.id_medicamento, med.nombre_medicamento, 
                           p.fecha_aplicacion, p.proximo_refuerzo
                    FROM plan_sanitario p
                    INNER JOIN mascota m ON p.id_mascota = m.id_mascota
                    INNER JOIN medicamento med ON p.id_medicamento = med.id_medicamento
                    WHERE p.estado = 1";
            if ($busqueda !== null && $busqueda !== '') {
                $sql .= " AND (CAST(p.id_plan AS CHAR) LIKE :param
                           OR CAST(p.id_mascota AS CHAR) LIKE :param
                           OR m.nombre LIKE :param
                           OR CAST(p.id_medicamento AS CHAR) LIKE :param
                           OR med.nombre_medicamento LIKE :param
                           OR p.fecha_aplicacion LIKE :param
                           OR p.proximo_refuerzo LIKE :param)";
            }
            $sql .= " ORDER BY p.id_plan DESC";
            if ($length !== null) {
                $sql .= " LIMIT :limite OFFSET :offset";
            }
            $stmt = $db->prepare($sql);
            if ($busqueda !== null && $busqueda !== '') {
                $stmt->bindValue(':param', '%' . $busqueda . '%', PDO::PARAM_STR);
            }
            if ($length !== null) {
                $stmt->bindValue(':limite', $length, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $start, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function actualizarPlan($datos) {
        try {
            $db = $this->getConexion();
            $sql = "UPDATE plan_sanitario SET id_mascota = ?, id_medicamento = ?, fecha_aplicacion = ?, proximo_refuerzo = ? WHERE id_plan = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $datos['id_mascota'],
                $datos['id_medicamento'],
                $datos['fecha_aplicacion'],
                $datos['proximo_refuerzo'],
                $datos['id_plan']
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminarPlan($id_plan) {
        try {
            $db = $this->getConexion();
            $sql = "UPDATE plan_sanitario SET estado = 0 WHERE id_plan = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$id_plan]);
        } catch (Exception $e) {
            return false;
        }
    }

     public function contarPlanesSanitarios(?string $busqueda = null): int {
        $conex = $this->getConexion();
        if ($busqueda === null || $busqueda === '') {
            $query = $conex->query("SELECT COUNT(*) FROM plan_sanitario WHERE estado = 1");
        } else {
            $query = $conex->prepare(
                "SELECT COUNT(*)
                 FROM plan_sanitario p
                 INNER JOIN mascota m ON p.id_mascota = m.id_mascota
                 INNER JOIN medicamento med ON p.id_medicamento = med.id_medicamento
                 WHERE p.estado = 1
                   AND (CAST(p.id_plan AS CHAR) LIKE :param
                        OR CAST(p.id_mascota AS CHAR) LIKE :param
                        OR m.nombre LIKE :param
                        OR CAST(p.id_medicamento AS CHAR) LIKE :param
                        OR med.nombre_medicamento LIKE :param
                        OR p.fecha_aplicacion LIKE :param
                        OR p.proximo_refuerzo LIKE :param)"
            );
            $query->bindValue(':param', '%' . $busqueda . '%', PDO::PARAM_STR);
            $query->execute();
        }

        return (int) $query->fetchColumn();
    }

    public function contarPlanesPendientes(): int {
        $query = $this->getConexion()->query(
            "SELECT COUNT(*) FROM plan_sanitario
             WHERE estado = 1 AND proximo_refuerzo <= CURRENT_DATE"
        );

        return (int) $query->fetchColumn();
	}
}
