<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;
use Exception;

class AlergiaModel extends ConexionBD {

    public function registrarAlergia($nombre, $tipo) {
        try {
            $db = $this->getConexion(); 
            $sql = "INSERT INTO alergia (nombre_alergia, tipo, estado) VALUES (?, ?, 1)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$nombre, $tipo]);
        } catch (Exception $e) {
            return false;
        }
    }

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

    public function actualizarAlergia($id, $nombre, $tipo) {
        try {
            $db = $this->getConexion();
            $sql = "UPDATE alergia SET nombre_alergia = ?, tipo = ? WHERE id_alergia = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$nombre, $tipo, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

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