<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ExamenClinicoModel extends ConexionBD
{
    private ?int $id_examen = null;
    private ?int $id_consulta = null;
    private ?string $peso = null;
    private ?string $cc = null;
    private ?string $temp_celsius = null;
    private ?string $pulso_yugular = null;
    private ?string $fr_rpm = null;
    private ?string $fc_lpm = null;
    private ?string $pulso_ppm = null;
    private ?string $tlc_seg = null;
    private ?string $tpc_seg = null;
    private ?string $pas = null;
    private ?string $pad = null;
    private ?string $prcnt_deshidratacion = null;
    private ?string $gangliios_palpables = null;
    private ?string $mucosas_visibles = null;
    private ?string $ectoparasitos = null;
    private ?string $actitud = null;
    private ?string $hallazgos = null;

    private const COLUMNAS = [
        'peso','cc','temp_celsius','pulso_yugular','fr_rpm','fc_lpm','pulso_ppm','tlc_seg',
        'tpc_seg','pas','pad','prcnt_deshidratacion','gangliios_palpables','mucosas_visibles',
        'ectoparasitos','actitud','hallazgos'
    ];

    private function hidratar(array $datos): void
    {
        foreach (self::COLUMNAS as $columna) {
            $this->{$columna} = $datos[$columna] ?? null;
        }
    }

    public function obtenerExamen(int $id): ?array
    {
        $consulta = $this->getConexion()->prepare("SELECT * FROM examen_clinico WHERE id_consulta = :id");
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function agregarExamen(int $id, array $datos): bool
    {
        $this->id_consulta = $id;
        $this->hidratar($datos);
        $columnas = self::COLUMNAS;
        $valores = [':id_consulta' => $this->id_consulta];
        $marcadores = [];
        foreach ($columnas as $columna) {
            $marcadores[] = ":$columna";
            $valores[":$columna"] = $this->{$columna};
        }
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO examen_clinico (id_consulta, " . implode(', ', $columnas) . ")
             VALUES (:id_consulta, " . implode(', ', $marcadores) . ")"
        );
        return $consulta->execute($valores);
    }

    public function actualizarExamen(int $id, array $datos): bool
    {
        $this->id_consulta = $id;
        $this->hidratar($datos);
        $columnas = self::COLUMNAS;
        $valores = [':id_consulta' => $this->id_consulta];
        $cambios = [];
        foreach ($columnas as $columna) {
            $cambios[] = "$columna = :$columna";
            $valores[":$columna"] = $this->{$columna};
        }
        $consulta = $this->getConexion()->prepare(
            "UPDATE examen_clinico SET " . implode(', ', $cambios) . " WHERE id_consulta = :id_consulta"
        );
        if (!$consulta->execute($valores)) {
            return false;
        }
        $existencia = $this->getConexion()->prepare(
            "SELECT 1 FROM examen_clinico WHERE id_consulta = :id_consulta"
        );
        $existencia->execute([':id_consulta' => $this->id_consulta]);
        if ($existencia->fetchColumn() !== false) {
            return true;
        }
        return $this->agregarExamen($id, $datos);
    }
}
