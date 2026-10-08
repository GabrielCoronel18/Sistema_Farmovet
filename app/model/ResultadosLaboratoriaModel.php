<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class ResultadosLaboratoriaModel
{
    private const COLUMNAS = [
        'hematologia_completa', 'coprologia', 'quimica_sanguinea', 'uro_sangre', 'uro_urob',
        'uro_bli', 'uro_prot', 'uro_nitritos', 'uro_cetona', 'uro_glucosa', 'uro_ph', 'uro_leu',
        'uro_densidad', 'uro_microorganismos', 'uro_celulas', 'uro_cilindros', 'uro_cristales',
        'descarte', 'piel_otros', 'snap', 'observaciones'
    ];

    public function agregar(PDO $db, int $idConsulta, array $datos): void
    {
        $campos = implode(', ', self::COLUMNAS);
        $marcadores = implode(', ', array_fill(0, count(self::COLUMNAS), '?'));
        $stmt = $db->prepare("INSERT INTO resultado_laboratorio (id_consulta, {$campos}) VALUES (?, {$marcadores})");
        $stmt->execute(array_merge([$idConsulta], $this->obtenerValores($datos)));
    }

    public function obtenerPorConsulta(PDO $db, int $idConsulta): ?array
    {
        $stmt = $db->prepare('SELECT * FROM resultado_laboratorio WHERE id_consulta = ?');
        $stmt->execute([$idConsulta]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function obtenerPorConsultas(PDO $db, array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $db->prepare("SELECT * FROM resultado_laboratorio WHERE id_consulta IN ({$marcadores})");
        $stmt->execute($idConsultas);
        $registros = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $registros[(int) $registro['id_consulta']] = $registro;
        }
        return $registros;
    }

    public function actualizar(PDO $db, int $idConsulta, array $datos): void
    {
        $asignaciones = implode(', ', array_map(static fn(string $columna): string => "{$columna} = ?", self::COLUMNAS));
        $stmt = $db->prepare("UPDATE resultado_laboratorio SET {$asignaciones} WHERE id_consulta = ?");
        $stmt->execute(array_merge($this->obtenerValores($datos), [$idConsulta]));
    }

    private function obtenerValores(array $datos): array
    {
        return array_map(static fn(string $columna) => $datos[$columna] ?? null, self::COLUMNAS);
    }
}