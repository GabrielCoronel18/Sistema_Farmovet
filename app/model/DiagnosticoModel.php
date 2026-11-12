<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;


class DiagnosticoModel extends ConexionBD
{
    private ?int $id_diagnostico = null;
    private ?int $id_consulta = null;
    private ?int $id_patologia = null;
    private ?string $tipo_diagnostico = null;

    private function hidratar(array $datos): void
    {
        $this->id_diagnostico = isset($datos['id_diagnostico']) ? (int) $datos['id_diagnostico'] : null;
        $this->id_patologia = isset($datos['id_patologia']) ? (int) $datos['id_patologia'] : null;
        $this->tipo_diagnostico = $datos['tipo_diagnostico'] ?? null;
    }

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
        $this->id_consulta = $id;
        $this->hidratar($datos);
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO diagnostico (id_consulta, id_patologia, tipo_diagnostico)
             VALUES (:id_consulta, :id_patologia, :tipo_diagnostico)"
        );
        return $consulta->execute([
            ':id_consulta' => $id,
            ':id_patologia' => $this->id_patologia,
            ':tipo_diagnostico' => $this->tipo_diagnostico
        ]);
    }

    public function actualizarDiagnostico(int $id, array $datos): bool
    {
        $this->id_diagnostico = $id;
        $this->hidratar($datos);
        $consulta = $this->getConexion()->prepare(
            "UPDATE diagnostico SET id_patologia = :id_patologia,
             tipo_diagnostico = :tipo_diagnostico WHERE id_diagnostico = :id"
        );
        return $consulta->execute([
            ':id' => $id,
            ':id_patologia' => $this->id_patologia,
            ':tipo_diagnostico' => $this->tipo_diagnostico
        ]);
    }

    public function eliminarNoIncluidos(int $idConsulta, array $ids): bool
    {
        $ids = array_values(array_filter(array_map('intval', $ids), fn (int $id): bool => $id > 0));
        $parametros = [':id_consulta' => $idConsulta];
        $condicion = '';

        if ($ids) {
            $marcadores = [];
            foreach ($ids as $indice => $id) {
                $marcador = ':id_' . $indice;
                $marcadores[] = $marcador;
                $parametros[$marcador] = $id;
            }
            $condicion = ' AND id_diagnostico NOT IN (' . implode(', ', $marcadores) . ')';
        }

        $consulta = $this->getConexion()->prepare(
            'DELETE FROM diagnostico WHERE id_consulta = :id_consulta' . $condicion
        );
        return $consulta->execute($parametros);
    }
}
