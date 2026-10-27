<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class DiagnosticoModel extends ConexionBD
{
    public ?int $id_diagnostico = null;
    public ?int $id_consulta = null;
    public ?int $id_patologia = null;
    public ?string $tipo_diagnostico = null;

    public function cargarDatos(array $datos): void
    {
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor === '' ? null : $valor;
            }
        }
    }

    public function agregar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'INSERT INTO diagnostico (id_consulta, id_patologia, tipo_diagnostico)
             VALUES (:id_consulta, :id_patologia, :tipo_diagnostico)'
        );
        $stmt->execute([
            ':id_consulta' => $this->id_consulta,
            ':id_patologia' => $this->id_patologia,
            ':tipo_diagnostico' => $this->tipo_diagnostico
        ]);
        $this->id_diagnostico = (int) $this->getConexion()->lastInsertId();
    }

    public function obtenerPorConsulta(int $idConsulta): array
    {
        $stmt = $this->getConexion()->prepare(
            'SELECT d.id_diagnostico, d.id_consulta, d.id_patologia, d.tipo_diagnostico,
                    p.nombre AS patologia
             FROM diagnostico d
             INNER JOIN patologia p ON p.id_patologia = d.id_patologia
             WHERE d.id_consulta = ?
             ORDER BY d.id_diagnostico'
        );
        $stmt->execute([$idConsulta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorConsultas(array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $this->getConexion()->prepare(
            "SELECT d.id_diagnostico, d.id_consulta, d.id_patologia, d.tipo_diagnostico,
                    p.nombre AS patologia
             FROM diagnostico d
             INNER JOIN patologia p ON p.id_patologia = d.id_patologia
             WHERE d.id_consulta IN ({$marcadores})
             ORDER BY d.id_diagnostico"
        );
        $stmt->execute($idConsultas);
        $registros = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $registros[(int) $registro['id_consulta']][] = $registro;
        }
        return $registros;
    }

    public function actualizar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'UPDATE diagnostico SET id_patologia = :id_patologia, tipo_diagnostico = :tipo_diagnostico
             WHERE id_diagnostico = :id_diagnostico AND id_consulta = :id_consulta'
        );
        $stmt->execute([
            ':id_patologia' => $this->id_patologia,
            ':tipo_diagnostico' => $this->tipo_diagnostico,
            ':id_diagnostico' => $this->id_diagnostico,
            ':id_consulta' => $this->id_consulta
        ]);
    }

    public function eliminar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'DELETE FROM diagnostico WHERE id_diagnostico = ? AND id_consulta = ?'
        );
        $stmt->execute([$this->id_diagnostico, $this->id_consulta]);
    }
}
