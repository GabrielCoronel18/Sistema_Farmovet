<?php
namespace Gabriel\SistemaFarmovet\model;

use PDO;

class RecipeModel
{
    public function agregar(PDO $db, int $idConsulta, array $datos): void
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

    public function obtenerPorConsulta(PDO $db, int $idConsulta): array
    {
        $stmt = $db->prepare(
            "SELECT r.id_recipe, r.id_consulta, r.id_medicamento,
                    m.nombre_medicamento, r.dosis, r.frecuencia, r.duracion
             FROM recipe r
             INNER JOIN medicamento m ON m.id_medicamento = r.id_medicamento
             WHERE r.id_consulta = ?
             ORDER BY r.id_recipe"
        );
        $stmt->execute([$idConsulta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorConsultas(PDO $db, array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $db->prepare(
            "SELECT r.id_recipe, r.id_consulta, r.id_medicamento,
                    m.nombre_medicamento, r.dosis, r.frecuencia, r.duracion
             FROM recipe r
             INNER JOIN medicamento m ON m.id_medicamento = r.id_medicamento
             WHERE r.id_consulta IN ({$marcadores})
             ORDER BY r.id_recipe"
        );
        $stmt->execute($idConsultas);
        $registros = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $registros[(int) $registro['id_consulta']][] = $registro;
        }
        return $registros;
    }

    public function actualizar(PDO $db, int $idRecipe, int $idConsulta, array $datos): void
    {
        $stmt = $db->prepare(
            "UPDATE recipe
             SET id_medicamento = ?, dosis = ?, frecuencia = ?, duracion = ?
             WHERE id_recipe = ? AND id_consulta = ?"
        );
        $stmt->execute([
            $datos['id_medicamento'],
            $datos['dosis'],
            $datos['frecuencia'],
            $datos['duracion'],
            $idRecipe,
            $idConsulta
        ]);
    }

    public function eliminar(PDO $db, int $idRecipe, int $idConsulta): void
    {
        $stmt = $db->prepare('DELETE FROM recipe WHERE id_recipe = ? AND id_consulta = ?');
        $stmt->execute([$idRecipe, $idConsulta]);
    }
}