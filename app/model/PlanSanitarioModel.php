<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;
use Exception;

// Forzamos la inclusión de la conexión por si el autoload falla
require_once __DIR__ . '/../config/ConexionBD.php';

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

    public function consultarPlanes() {
        try {
            $db = $this->getConexion();
            $sql = "SELECT p.id_plan, p.id_mascota, m.nombre AS nombre_mascota, 
                           p.id_medicamento, med.nombre_medicamento, 
                           p.fecha_aplicacion, p.proximo_refuerzo
                    FROM plan_sanitario p
                    INNER JOIN mascota m ON p.id_mascota = m.id_mascota
                    INNER JOIN medicamento med ON p.id_medicamento = med.id_medicamento
                    WHERE p.estado = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
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
}
