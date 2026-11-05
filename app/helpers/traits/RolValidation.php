<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait RolValidation
{
    public function validateNombreRol(string $nombre): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u', trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras, espacios, guiones, paréntesis y barras.';
            return false;
        }
        return true;
    }
}