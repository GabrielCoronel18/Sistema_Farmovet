<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait PlanSanitarioValidation
{
    public function validateIdPlanSanitario(mixed $valor, string $campo): bool
    {
        if (filter_var($valor, FILTER_VALIDATE_INT) === false || (int) $valor <= 0) {
            $this->errors[$campo] = 'Debe seleccionar un registro válido.';
            return false;
        }
        return true;
    }

    public function validateFechaPlanSanitario(string $fecha, string $campo): bool
    {
        $fechaValida = \DateTime::createFromFormat('Y-m-d', $fecha);
        if (
            !$fechaValida ||
            $fechaValida->format('Y-m-d') !== $fecha
        ) {
            $this->errors[$campo] = 'Ingrese una fecha válida.';
            return false;
        }
        return true;
    }

    public function validateRangoFechasPlanSanitario(
        string $fechaAplicacion,
        string $proximoRefuerzo
    ): bool {
        $aplicacion = \DateTime::createFromFormat('Y-m-d', $fechaAplicacion);
        $refuerzo = \DateTime::createFromFormat('Y-m-d', $proximoRefuerzo);

        if ($aplicacion && $refuerzo && $refuerzo < $aplicacion) {
            $this->errors['proximo_refuerzo'] =
                'El próximo refuerzo no puede ser anterior a la fecha de aplicación.';
            return false;
        }
        return true;
    }
}
