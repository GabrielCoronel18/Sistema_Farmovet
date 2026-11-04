<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class RecipeModel extends ConexionBD
{
    private ?int $id_recipe = null;
    private ?int $id_consulta = null;
    private ?int $id_medicamento = null;
    private ?string $dosis = null;
    private ?string $frecuencia = null;
    private ?string $duracion = null;

    private function hidratar(array $datos): void
    {
        $this->id_recipe = isset($datos['id_recipe']) ? (int) $datos['id_recipe'] : null;
        $this->id_medicamento = isset($datos['id_medicamento']) ? (int) $datos['id_medicamento'] : null;
        $this->dosis = $datos['dosis'] ?? null;
        $this->frecuencia = $datos['frecuencia'] ?? null;
        $this->duracion = $datos['duracion'] ?? null;
    }

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
        $this->id_consulta = $id;
        $this->hidratar($datos);
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO recipe (id_consulta, id_medicamento, dosis, frecuencia, duracion)
             VALUES (:id_consulta, :id_medicamento, :dosis, :frecuencia, :duracion)"
        );
        return $consulta->execute([
            ':id_consulta' => $id,
            ':id_medicamento' => $this->id_medicamento,
            ':dosis' => $this->dosis,
            ':frecuencia' => $this->frecuencia,
            ':duracion' => $this->duracion
        ]);
    }

    public function actualizarRecipe(int $id, array $datos): bool
    {
        $this->id_recipe = $id;
        $this->hidratar($datos);
        $consulta = $this->getConexion()->prepare(
            "UPDATE recipe SET id_medicamento = :id_medicamento, dosis = :dosis,
             frecuencia = :frecuencia, duracion = :duracion WHERE id_recipe = :id"
        );
        return $consulta->execute([
            ':id' => $id,
            ':id_medicamento' => $this->id_medicamento,
            ':dosis' => $this->dosis,
            ':frecuencia' => $this->frecuencia,
            ':duracion' => $this->duracion
        ]);
    }

    public function eliminarNoIncluidos(int $idConsulta, array $ids): bool
    {
        $ids = array_values(array_filter(array_map('intval', $ids), fn (int $id): bool => $id > 0));
        $parametros = [':id_consulta' => $idConsulta];
        $condicion = '';

        if ($ids) {
            $marcadores = [];
            foreach ($ids as $indice => $id) {
                $marcador = ':id_' . $indice;
                $marcadores[] = $marcador;
                $parametros[$marcador] = $id;
            }
            $condicion = ' AND id_recipe NOT IN (' . implode(', ', $marcadores) . ')';
        }

        $consulta = $this->getConexion()->prepare(
            'DELETE FROM recipe WHERE id_consulta = :id_consulta' . $condicion
        );
        return $consulta->execute($parametros);
    }
}
