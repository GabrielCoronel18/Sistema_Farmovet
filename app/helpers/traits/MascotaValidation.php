<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait MascotaValidation
{
    public function validateNombreMascota(string $nombre): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ \'-]+[\p{L}]+)*$/u', trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras, espacios, apóstrofes y guiones.';
            return false;
        }
        return true;
    }

    public function validatePelajeMascota(string $pelaje): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ \/()-]+[\p{L}]+)*$/u', trim($pelaje))) {
            $this->errors['pelaje'] =
                'El pelaje solo puede contener letras, espacios, guiones, paréntesis y barras.';
            return false;
        }
        return true;
    }

    public function validateProcedenciaMascota(string $procedencia): bool
    {
        if (!preg_match('/^[\p{L}\p{N}]+(?:[\s.,()\/\'-]+[\p{L}\p{N}]+)*$/u', trim($procedencia))) {
            $this->errors['procedencia'] =
                'La procedencia contiene caracteres no permitidos.';
            return false;
        }
        return true;
    }
}
