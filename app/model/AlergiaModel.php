<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;
use Exception;

// Forzamos la inclusión de la conexión por si el autoload falla
require_once __DIR__ . '/../config/ConexionBD.php';

class AlergiaModel extends ConexionBD {

    /**
     * RF 5.6.1 Registrar Alergia
     * Registra una nueva alergia con estado activo (1).
     */
    public function registrarAlergia($datos) {
        try {
            $db = $this->getConexion(); 
            $sql = "INSERT INTO alergia (nombre_alergia, tipo, estado) VALUES (?, ?, 1)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $datos['nombre_alergia'],
                $datos['tipo']
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * RF 5.6.2 Consultar Alergia
     * Obtiene todas las alergias activas (estado = 1).
     */
    public function consultarAlergias() {
        try {
            $db = $this->getConexion();
            $sql = "SELECT id_alergia, nombre_alergia, tipo, estado 
                    FROM alergia 
                    WHERE estado = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Consultar una alergia específica por su ID.
     */
    public function obtenerAlergiaPorId($id_alergia) {
        try {
            $db = $this->getConexion();
            $sql = "SELECT id_alergia, nombre_alergia, tipo, estado 
                    FROM alergia 
                    WHERE id_alergia = ? AND estado = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id_alergia]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * RF 5.6.2.1 Actualizar Alergia
     * Modifica los datos de una alergia existente.
     */
    public function actualizarAlergia($datos) {
        try {
            $db = $this->getConexion();
            $sql = "UPDATE alergia SET nombre_alergia = ?, tipo = ? WHERE id_alergia = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $datos['nombre_alergia'],
                $datos['tipo'],
                $datos['id_alergia']
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * RF 5.6.2.2 Eliminar Alergia
     * Realiza un borrado lógico cambiando el estado a 0.
     */
    public function eliminarAlergia($id_alergia) {
        try {
            $db = $this->getConexion();
            $sql = "UPDATE alergia SET estado = 0 WHERE id_alergia = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$id_alergia]);
        } catch (Exception $e) {
            return false;
        }
    }
}