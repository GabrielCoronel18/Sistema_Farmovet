<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class ExamenClinicoModel
{
    private const COLUMNAS = [
        'peso', 'cc', 'temp_celsius', 'pulso_yugular', 'fr_rpm', 'fc_lpm', 'pulso_ppm',
        'tlc_seg', 'tpc_seg', 'pas', 'pad', 'prcnt_deshidratacion', 'gangliios_palpables',
        'mucosas_visibles', 'ectoparasitos', 'actitud', 'hallazgos'
    ];

    public function agregar(PDO $db, int $idConsulta, array $datos): void
    {
        $campos = implode(', ', self::COLUMNAS);
        $marcadores = implode(', ', array_fill(0, count(self::COLUMNAS), '?'));
        $stmt = $db->prepare("INSERT INTO examen_clinico (id_consulta, {$campos}) VALUES (?, {$marcadores})");
        $stmt->execute(array_merge([$idConsulta], $this->obtenerValores($datos)));
    }

    public function obtenerPorConsulta(PDO $db, int $idConsulta): ?array
    {
        $stmt = $db->prepare('SELECT * FROM examen_clinico WHERE id_consulta = ?');
        $stmt->execute([$idConsulta]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function obtenerPorConsultas(PDO $db, array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $db->prepare("SELECT * FROM examen_clinico WHERE id_consulta IN ({$marcadores})");
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
        $stmt = $db->prepare("UPDATE examen_clinico SET {$asignaciones} WHERE id_consulta = ?");
        $stmt->execute(array_merge($this->obtenerValores($datos), [$idConsulta]));
    }

    private function obtenerValores(array $datos): array
    {
        return array_map(static fn(string $columna) => $datos[$columna] ?? null, self::COLUMNAS);
    }
}