<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait EspeciesValidation
{
    public function validateNombreEspecie(string $nombre): bool
    {
        if (!preg_match('/^[\p{L}]+(?: +[\p{L}]+)*$/u', trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras y espacios.';
            return false;
        }
        return true;
    }
}