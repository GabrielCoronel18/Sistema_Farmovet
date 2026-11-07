<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait TipoMedicamentoValidation
{
    public function validateNombreTipoMedicamento(string $nombre): bool
    {
        $patron = '/^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u';

        if (!preg_match($patron, trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras, espacios, guiones, paréntesis y barras.';
            return false;
        }

        return true;
    }
}
