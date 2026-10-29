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

    public function obtenerDiagnosticos(int $id): array
    {
        $consulta = $this->getConexion()->prepare(
            "SELECT diagnostico.*, patologia.nombre AS patologia
             FROM diagnostico
             INNER JOIN patologia ON patologia.id_patologia = diagnostico.id_patologia
             WHERE diagnostico.id_consulta = :id"
        );
        $consulta->execute([':id' => $id]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarDiagnostico(int $id, array $datos): bool
    {
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO diagnostico (id_consulta, id_patologia, tipo_diagnostico)
             VALUES (:id_consulta, :id_patologia, :tipo_diagnostico)"
        );
        return $consulta->execute([
            ':id_consulta' => $id,
            ':id_patologia' => $datos['id_patologia'],
            ':tipo_diagnostico' => $datos['tipo_diagnostico']
        ]);
    }

    public function editarDiagnostico(int $id, array $datos): bool
    {
        $consulta = $this->getConexion()->prepare(
            "UPDATE diagnostico SET id_patologia = :id_patologia,
             tipo_diagnostico = :tipo_diagnostico WHERE id_diagnostico = :id"
        );
        return $consulta->execute([
            ':id' => $id,
            ':id_patologia' => $datos['id_patologia'],
            ':tipo_diagnostico' => $datos['tipo_diagnostico']
        ]);
    }
}
