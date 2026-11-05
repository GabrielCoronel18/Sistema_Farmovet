<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait UsuarioValidation
{
    public function validateCedulaUsuario(string $cedula): bool
    {
        if (!preg_match('/^(?:\d{10,12}|[VEJGP]-\d{6,10})$/i', strtoupper(trim($cedula)))) {
            $this->errors['cedula'] = 'Use una cédula numérica o el formato V-12345678.';
            return false;
        }
        return true;
    }

    public function validateNombreUsuario(string $valor, string $campo): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ \'-]+[\p{L}]+)*$/u', trim($valor))) {
            $this->errors[$campo] = 'Solo se permiten letras, espacios, apóstrofes y guiones.';
            return false;
        }
        return true;
    }

    public function validateTelefonoUsuario(string $telefono): bool
    {
        if ($telefono !== '' && !preg_match('/^\d{9,11}$/', trim($telefono))) {
            $this->errors['telefono'] = 'El teléfono debe contener entre 9 y 11 números.';
            return false;
        }
        return true;
    }

    public function validateCorreoUsuario(string $correo): bool
    {
        if (!filter_var(trim($correo), FILTER_VALIDATE_EMAIL)) {
            $this->errors['correo'] = 'Ingrese un correo electrónico válido.';
            return false;
        }
        return true;
    }

    public function validateContraseñaUsuario(string $contraseña, bool $obligatoria = true): bool
    {
        if (!$obligatoria && trim($contraseña) === '') {
            return true;
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,72}$/', $contraseña)) {
            $this->errors['contraseña'] =
                'Debe tener entre 8 y 72 caracteres, mayúscula, minúscula, número y símbolo.';
            return false;
        }
        return true;
    }
}
