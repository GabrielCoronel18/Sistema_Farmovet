<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait ClienteValidation
{
    public function validateNombreCliente(string $valor, string $campo): bool
    {
        if (!preg_match('/^[\p{L}]+(?:[ \'-]+[\p{L}]+)*$/u', trim($valor))) {
            $this->errors[$campo] = 'Solo se permiten letras, espacios, apóstrofes y guiones.';
            return false;
        }
        return true;
    }

    public function validateCedulaCliente(string $cedula): bool
    {
        $cedula = strtoupper(trim($cedula));
         // Cambiamos el patrón: Solo de 6 a 9 números (sin letras ni guiones)
        $formatoValido = preg_match('/^\d{6,9}$/', $cedula);

        if (!$formatoValido) {
            $this->errors['cedula'] =
                 'La cédula debe contener solo números (entre 6 y 9 dígitos).';
            return false;
        }
        return true;
    }

    public function validateCorreoCliente(string $correo): bool
    {
        if (!preg_match('/^[A-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?(?:\.[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?)+$/i', trim($correo))) {
            $this->errors['correo'] =
                'Ingrese un correo válido, por ejemplo: usuario@dominio.com.';
            return false;
        }
        return true;
    }

    public function validateTelefonoCliente(string $telefono): bool
    {
        if (!preg_match('/^\d{9,11}$/', trim($telefono))) {
            $this->errors['telefono'] = 'El teléfono debe contener entre 9 y 11 números.';
            return false;
        }
        return true;
    }

    public function validateDireccionCliente(string $direccion): bool
    {
        if (!preg_match('/^[\p{L}\p{N}]+(?:[\s.,#()\/\'-]+[\p{L}\p{N}]+)*$/u', trim($direccion))) {
            $this->errors['direccion'] = 'La dirección contiene caracteres no permitidos.';
            return false;
        }
        return true;
    }
}
