<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class AnamnesisModel
{
    public function registrar(PDO $db, int $idConsulta, array $datos): void
    {
        $columnas = [
            'motivo_consulta', 'inicio_enfermedad', 'examenes_efectuados', 'tratamientos_realizados',
            'ult_desp_interna', 'ult_desp_int_producto', 'ult_desp_externa', 'ult_desp_ext_producto',
            'ultima_vacunacion', 'tipo_alimentacion', 'frec_alimentacion', 'apetito', 'ingesta_agua',
            'contacto_animal', 'vomito', 'heces', 'miccion', 'fch_ultimo_celo', 'prod_higiene',
            'frec_higiene', 'int_ambiente', 'ext_ambiente', 'act_ectoparasitos', 'ant_ectoparasitos'
        ];
        $valores = array_map(static fn(string $columna) => $datos[$columna] ?? null, $columnas);
        $campos = implode(', ', $columnas);
        $marcadores = implode(', ', array_fill(0, count($columnas), '?'));
        $stmt = $db->prepare("INSERT INTO anamnesis (id_consulta, {$campos}) VALUES (?, {$marcadores})");
        $stmt->execute(array_merge([$idConsulta], $valores));
    }
}