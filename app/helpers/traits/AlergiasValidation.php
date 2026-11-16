<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait AlergiasValidation
{
    public function validateNombreAlergia(string $nombre): bool
    {
        if (!preg_match('/^(?=.*\p{L})[\p{L} ()\/-]+$/u', trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras, espacios, guiones, paréntesis y barras, e incluir al menos una letra.';
            return false;
        }
        return true;
    }
    
    public function validateTipoAlergia(string $tipo): bool
    {
        if (!preg_match('/^(?=.*\p{L})[\p{L} ()\/-]+$/u', trim($tipo))) {
            $this->errors['tipo'] =
                'El tipo solo puede contener letras, espacios, guiones, paréntesis y barras, e incluir al menos una letra.';
            return false;
        }
        return true;
    }
}
