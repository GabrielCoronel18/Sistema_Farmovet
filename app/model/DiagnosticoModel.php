<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class DiagnosticoModel
{
    public function registrar(PDO $db, int $idConsulta, array $datos): void
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
}