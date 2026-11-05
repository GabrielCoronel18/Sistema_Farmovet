<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait CirugiaValidation
{
    public function validateNombreCirugia(string $nombre): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u', trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras, espacios, guiones, paréntesis y barras.';
            return false;
        }
        return true;
    }
}
