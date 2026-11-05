<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait RazaValidation
{
    public function validateNombreRaza(string $nombre): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ \'-]+[\p{L}]+)*$/u', trim($nombre))) {
            $this->errors['nombre_raza'] =
                'El nombre solo puede contener letras, espacios, apóstrofes y guiones.';
            return false;
        }
        return true;
    }
}
