<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait RolValidation
{
    public function validateNombreRol(string $nombre): bool
    {
        if (!preg_match('/^(?=.*\p{L})[\p{L} ()\/-]+$/u', trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras, espacios, guiones, paréntesis y barras, y debe incluir al menos una letra.';
            return false;
        }
        return true;
    }
}