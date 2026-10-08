<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class DiagnosticoModel
{
    public function agregar(PDO $db, int $idConsulta, array $datos): void
    {
        $stmt = $db->prepare(
            "INSERT INTO diagnostico (id_consulta, id_patologia, tipo_diagnostico) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $idConsulta,
            $datos['id_patologia'],
            $datos['tipo_diagnostico']
        ]);
    }

    public function obtenerPorConsulta(PDO $db, int $idConsulta): array
    {
        $stmt = $db->prepare(
            "SELECT d.id_diagnostico, d.id_consulta, d.id_patologia,
                    d.tipo_diagnostico, p.nombre AS patologia
             FROM diagnostico d
             INNER JOIN patologia p ON p.id_patologia = d.id_patologia
             WHERE d.id_consulta = ?
             ORDER BY d.id_diagnostico"
        );
        $stmt->execute([$idConsulta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorConsultas(PDO $db, array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $db->prepare(
            "SELECT d.id_diagnostico, d.id_consulta, d.id_patologia,
                    d.tipo_diagnostico, p.nombre AS patologia
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

    public function actualizar(PDO $db, int $idDiagnostico, int $idConsulta, array $datos): void
    {
        $stmt = $db->prepare(
            "UPDATE diagnostico
             SET id_patologia = ?, tipo_diagnostico = ?
             WHERE id_diagnostico = ? AND id_consulta = ?"
        );
        $stmt->execute([
            $datos['id_patologia'],
            $datos['tipo_diagnostico'],
            $idDiagnostico,
            $idConsulta
        ]);
    }

    public function eliminar(PDO $db, int $idDiagnostico, int $idConsulta): void
    {
        $stmt = $db->prepare('DELETE FROM diagnostico WHERE id_diagnostico = ? AND id_consulta = ?');
        $stmt->execute([$idDiagnostico, $idConsulta]);
    }
}