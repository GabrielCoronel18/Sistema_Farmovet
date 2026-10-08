<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class AnamnesisModel
{
    private const COLUMNAS = [
        'motivo_consulta', 'inicio_enfermedad', 'examenes_efectuados', 'tratamientos_realizados',
        'ult_desp_interna', 'ult_desp_int_producto', 'ult_desp_externa', 'ult_desp_ext_producto',
        'ultima_vacunacion', 'tipo_alimentacion', 'frec_alimentacion', 'apetito', 'ingesta_agua',
        'contacto_animal', 'vomito', 'heces', 'miccion', 'fch_ultimo_celo', 'prod_higiene',
        'frec_higiene', 'int_ambiente', 'ext_ambiente', 'act_ectoparasitos', 'ant_ectoparasitos'
    ];

    public function agregar(PDO $db, int $idConsulta, array $datos): void
    {
        $campos = implode(', ', self::COLUMNAS);
        $marcadores = implode(', ', array_fill(0, count(self::COLUMNAS), '?'));
        $stmt = $db->prepare("INSERT INTO anamnesis (id_consulta, {$campos}) VALUES (?, {$marcadores})");
        $stmt->execute(array_merge([$idConsulta], $this->obtenerValores($datos)));
    }

    public function obtenerPorConsulta(PDO $db, int $idConsulta): ?array
    {
        $stmt = $db->prepare('SELECT * FROM anamnesis WHERE id_consulta = ?');
        $stmt->execute([$idConsulta]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function obtenerPorConsultas(PDO $db, array $idConsultas): array
    {
        return $this->obtenerRegistrosPorConsultas($db, $idConsultas);
    }

    public function actualizar(PDO $db, int $idConsulta, array $datos): void
    {
        $asignaciones = implode(', ', array_map(static fn(string $columna): string => "{$columna} = ?", self::COLUMNAS));
        $stmt = $db->prepare("UPDATE anamnesis SET {$asignaciones} WHERE id_consulta = ?");
        $stmt->execute(array_merge($this->obtenerValores($datos), [$idConsulta]));
    }

    private function obtenerValores(array $datos): array
    {
        return array_map(static fn(string $columna) => $datos[$columna] ?? null, self::COLUMNAS);
    }

    private function obtenerRegistrosPorConsultas(PDO $db, array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $db->prepare("SELECT * FROM anamnesis WHERE id_consulta IN ({$marcadores})");
        $stmt->execute($idConsultas);
        $registros = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $registros[(int) $registro['id_consulta']] = $registro;
        }
        return $registros;
    }
}