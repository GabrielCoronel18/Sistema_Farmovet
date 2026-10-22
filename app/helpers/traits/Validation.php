<?php

namespace Gabriel\SistemaFarmovet\helpers\traits;
trait Validation
{
    protected array $errors = [];

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

    public function validateRole(int $role, array $allowed = [1, 2, 3]): bool
    {
        if (!in_array($role, $allowed, true)) {
            $this->errors['role'] = "El rol seleccionado no es válido.";
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