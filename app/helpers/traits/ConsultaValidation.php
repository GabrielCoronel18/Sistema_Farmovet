<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

use Gabriel\SistemaFarmovet\model\AnamnesisModel;
use Gabriel\SistemaFarmovet\model\ExamenClinicoModel;
use Gabriel\SistemaFarmovet\model\ResultadosLaboratorioModel;
use Gabriel\SistemaFarmovet\model\DiagnosticoModel;
use Gabriel\SistemaFarmovet\model\RecipeModel;

trait ConsultaValidation
{
    public function validateTextoConsulta(string $valor,string $campo,int $maximo, bool $obligatorio = false
    ): bool {
        $valor = trim($valor);
        if ($obligatorio && $valor === '') {
            $this->errors[$campo] = 'Este campo es obligatorio.';
            return false;
        }
        if ($valor !== '' && mb_strlen($valor) > $maximo) {
            $this->errors[$campo] = "No debe exceder los {$maximo} caracteres.";
            return false;
        }
        return true;
    }

    public function validatePesoConsulta(string $peso): bool
    {
        if ($peso !== '' && (!is_numeric($peso) || (float) $peso < 0)) {
            $this->errors['peso_consulta'] = 'Ingrese un peso válido mayor o igual a cero.';
            return false;
        }
        return true;
    }
   public function validateActualizarAnamnesis($conexion, $id, array $anamnesis): void {
        if (!(new AnamnesisModel($conexion))->actualizarAnamnesis($id, $anamnesis)) {
            throw new \PDOException('No fue posible actualizar la anamnesis.');
        }
    }

    public function validateActualizarExamen($conexion, $id, array $examen): void {
        if (!(new ExamenClinicoModel($conexion))->actualizarExamen($id, $examen)) {
            throw new \PDOException('No fue posible actualizar el examen clinico.');
        }
    }

    public function validateActualizarLaboratorio($conexion, $id, array $laboratorio): void {
        if (!(new ResultadosLaboratorioModel($conexion))->actualizarResultado($id, $laboratorio)) {
            throw new \PDOException('No fue posible actualizar los resultados del laboratorio.');
        }
    }

    public function validateActualizarDiagnosticos($actualizado): void {
        if (!$actualizado) {
            throw new \PDOException('No fue posible actualizar los diagnósticos.');
        }
    }

    public function validateActualizarRecetas($actualizado): void {
        if (!$actualizado) {
            throw new \PDOException('No fue posible actualizar las recetas.');
        }
    }

    public function validateEliminarDiagnosticos($conexion, $id, array $diagnosticos): void {
        $idsDiagnosticos = array_column($diagnosticos, 'id_diagnostico');
        if (!(new DiagnosticoModel($conexion))->eliminarNoIncluidos($id, $idsDiagnosticos)) {
            throw new \PDOException('No fue posible eliminar los diagnósticos quitados.');
        }
    }

    public function validateEliminarRecetas($conexion, $id, array $recetas): void {
        $idsRecetas = array_column($recetas, 'id_recipe');
        if (!(new RecipeModel($conexion))->eliminarNoIncluidos($id, $idsRecetas)) {
            throw new \PDOException('No fue posible eliminar las recetas quitadas.');
        }
    }
}
