<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class RecipeModel extends ConexionBD
{
    public ?int $id_recipe = null;
    public ?int $id_consulta = null;
    public ?int $id_medicamento = null;
    public ?string $dosis = null;
    public ?string $frecuencia = null;
    public ?string $duracion = null;

    public function obtenerRecipes(int $id): array
    {
        $consulta = $this->getConexion()->prepare(
            "SELECT recipe.*, medicamento.nombre_medicamento
             FROM recipe
             INNER JOIN medicamento ON medicamento.id_medicamento = recipe.id_medicamento
             WHERE recipe.id_consulta = :id"
        );
        $consulta->execute([':id' => $id]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarRecipe(int $id, array $datos): bool
    {
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO recipe (id_consulta, id_medicamento, dosis, frecuencia, duracion)
             VALUES (:id_consulta, :id_medicamento, :dosis, :frecuencia, :duracion)"
        );
        return $consulta->execute([
            ':id_consulta' => $id,
            ':id_medicamento' => $datos['id_medicamento'],
            ':dosis' => $datos['dosis'],
            ':frecuencia' => $datos['frecuencia'],
            ':duracion' => $datos['duracion']
        ]);
    }

    public function editarRecipe(int $id, array $datos): bool
    {
        $consulta = $this->getConexion()->prepare(
            "UPDATE recipe SET id_medicamento = :id_medicamento, dosis = :dosis,
             frecuencia = :frecuencia, duracion = :duracion WHERE id_recipe = :id"
        );
        return $consulta->execute([
            ':id' => $id,
            ':id_medicamento' => $datos['id_medicamento'],
            ':dosis' => $datos['dosis'],
            ':frecuencia' => $datos['frecuencia'],
            ':duracion' => $datos['duracion']
        ]);
    }
}
