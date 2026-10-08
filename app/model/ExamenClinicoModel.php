<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class ExamenClinicoModel
{
    public function registrar(PDO $db, int $idConsulta, array $datos): void
    {
        $columnas = [
            'peso', 'cc', 'temp_celsius', 'pulso_yugular', 'fr_rpm', 'fc_lpm', 'pulso_ppm',
            'tlc_seg', 'tpc_seg', 'pas', 'pad', 'prcnt_deshidratacion', 'gangliios_palpables',
            'mucosas_visibles', 'ectoparasitos', 'actitud', 'hallazgos'
        ];
        $valores = array_map(static fn(string $columna) => $datos[$columna] ?? null, $columnas);
        $campos = implode(', ', $columnas);
        $marcadores = implode(', ', array_fill(0, count($columnas), '?'));
        $stmt = $db->prepare("INSERT INTO examen_clinico (id_consulta, {$campos}) VALUES (?, {$marcadores})");
        $stmt->execute(array_merge([$idConsulta], $valores));
    }
}