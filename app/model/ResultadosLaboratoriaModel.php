<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class ResultadosLaboratoriaModel
{
    public function registrar(PDO $db, int $idConsulta, array $datos): void
    {
        $columnas = [
            'hematologia_completa', 'coprologia', 'quimica_sanguinea', 'uro_sangre', 'uro_urob',
            'uro_bli', 'uro_prot', 'uro_nitritos', 'uro_cetona', 'uro_glucosa', 'uro_ph', 'uro_leu',
            'uro_densidad', 'uro_microorganismos', 'uro_celulas', 'uro_cilindros', 'uro_cristales',
            'descarte', 'piel_otros', 'snap', 'observaciones'
        ];
        $valores = array_map(static fn(string $columna) => $datos[$columna] ?? null, $columnas);
        $campos = implode(', ', $columnas);
        $marcadores = implode(', ', array_fill(0, count($columnas), '?'));
        $stmt = $db->prepare(
            "INSERT INTO resultado_laboratorio (id_consulta, {$campos}) VALUES (?, {$marcadores})"
        );
        $stmt->execute(array_merge([$idConsulta], $valores));
    }
}