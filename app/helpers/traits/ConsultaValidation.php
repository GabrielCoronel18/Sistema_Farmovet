<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait ConsultaValidation
{
    public function validateTextoConsulta(
        string $valor,
        string $campo,
        int $maximo,
        bool $obligatorio = false
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
}
