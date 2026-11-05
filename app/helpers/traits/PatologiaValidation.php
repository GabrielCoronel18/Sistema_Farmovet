<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait PatologiaValidation
{
    public function validateNombrePatologia(string $nombre): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u', trim($nombre))) {
            $this->errors['nombre'] =
                'El nombre solo puede contener letras, espacios, guiones, paréntesis y barras.';
            return false;
        }
        return true;
    }

    public function validateTipoPatologia(string $tipo): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u', trim($tipo))) {
            $this->errors['tipo'] =
                'El tipo solo puede contener letras, espacios, guiones, paréntesis y barras.';
            return false;
        }
        return true;
    }

    public function validateSintomasPatologia(string $sintomas): bool
    {
        if (!preg_match('/^[\p{L}\p{N}]+(?:[\s.,;:()\/\'%-]+[\p{L}\p{N}]+)*$/u', trim($sintomas))) {
            $this->errors['sintomas'] = 'Los síntomas contienen caracteres no permitidos o no cumplen con la longitud requerida.';
            return false;
        }
        return true;
    }
}
