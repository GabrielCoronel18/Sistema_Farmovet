<?php
namespace Gabriel\SistemaFarmovet\helpers\traits;

trait Validation
{
    public array $errors = [];

    public function validateRequired(array $data, array $fields): bool
    {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                $this->errors[$field] = "El campo '{$field}' es obligatorio.";
            }
        }
        return empty($this->errors);
    }

    public function validateMinLength(string $value, string $field, int $min): bool
    {
        if (mb_strlen(trim($value)) < $min) {
            $this->errors[$field] = "El campo '{$field}' debe tener al menos {$min} caracteres.";
            return false;
        }
        return true;
    }

    public function validateMaxLength(string $value, string $field, int $max): bool
    {
        if (mb_strlen(trim($value)) > $max) {
            $this->errors[$field] = "El campo '{$field}' no debe exceder los {$max} caracteres.";
            return false;
        }
        return true;
    }

    public function validateEmail(string $value, string $field): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "El campo '{$field}' debe ser un correo electrónico válido.";
            return false;
        }
        return true;
    }

    public function validateInteger(mixed $value, string $field): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field] = "El campo '{$field}' debe ser un número entero.";
            return false;
        }
        return true;
    }

    public function validateNumeric(mixed $value, string $field): bool
    {
        if (!is_numeric($value)) {
            $this->errors[$field] = "El campo '{$field}' debe ser numérico.";
            return false;
        }
        return true;
    }

    public function validateInArray(mixed $value, string $field, array $allowed): bool
    {
        if (!in_array($value, $allowed, true)) {
            $this->errors[$field] = "El valor seleccionado para '{$field}' no es válido.";
            return false;
        }
        return true;
    }
    public function validateDateFormat(string $value, string $format, string $field): bool
    {
        $date = \DateTime::createFromFormat($format, $value);
        if (!$date || $date->format($format) !== $value) {
            $this->errors[$field] = "El campo '{$field}' debe tener el formato de fecha '{$format}'.";
            return false;
        }
        return true;
    }
    public function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }
}