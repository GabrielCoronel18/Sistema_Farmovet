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

    public function cargarDatos(array $datos): void
    {
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor === '' ? null : $valor;
            }
        }
    }

    public function agregar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'INSERT INTO recipe (id_consulta, id_medicamento, dosis, frecuencia, duracion)
             VALUES (:id_consulta, :id_medicamento, :dosis, :frecuencia, :duracion)'
        );
        $stmt->execute([
            ':id_consulta' => $this->id_consulta,
            ':id_medicamento' => $this->id_medicamento,
            ':dosis' => $this->dosis,
            ':frecuencia' => $this->frecuencia,
            ':duracion' => $this->duracion
        ]);
        $this->id_recipe = (int) $this->getConexion()->lastInsertId();
    }

    public function obtenerPorConsulta(int $idConsulta): array
    {
        $stmt = $this->getConexion()->prepare(
            'SELECT r.id_recipe, r.id_consulta, r.id_medicamento,
                    m.nombre_medicamento, r.dosis, r.frecuencia, r.duracion
             FROM recipe r
             INNER JOIN medicamento m ON m.id_medicamento = r.id_medicamento
             WHERE r.id_consulta = ?
             ORDER BY r.id_recipe'
        );
        $stmt->execute([$idConsulta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorConsultas(array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $this->getConexion()->prepare(
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

    public function actualizar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'UPDATE recipe
             SET id_medicamento = :id_medicamento, dosis = :dosis, frecuencia = :frecuencia, duracion = :duracion
             WHERE id_recipe = :id_recipe AND id_consulta = :id_consulta'
        );
        $stmt->execute([
            ':id_medicamento' => $this->id_medicamento,
            ':dosis' => $this->dosis,
            ':frecuencia' => $this->frecuencia,
            ':duracion' => $this->duracion,
            ':id_recipe' => $this->id_recipe,
            ':id_consulta' => $this->id_consulta
        ]);
    }

    public function eliminar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'DELETE FROM recipe WHERE id_recipe = ? AND id_consulta = ?'
        );
        $stmt->execute([$this->id_recipe, $this->id_consulta]);
    }
}
