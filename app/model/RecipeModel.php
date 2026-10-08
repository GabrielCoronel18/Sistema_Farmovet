<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class RecipeModel
{
    public function registrar(PDO $db, int $idConsulta, array $datos): void
    {
        $stmt = $db->prepare(
            "INSERT INTO recipe (id_consulta, id_medicamento, dosis, frecuencia, duracion)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $idConsulta,
            $datos['id_medicamento'],
            $datos['dosis'],
            $datos['frecuencia'],
            $datos['duracion']
        ]);
    }
}