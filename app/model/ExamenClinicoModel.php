<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ExamenClinicoModel extends ConexionBD
{
    public ?int $id_examen = null;
    public ?int $id_consulta = null;
    public ?string $peso = null;
    public ?string $cc = null;
    public ?string $temp_celsius = null;
    public ?string $pulso_yugular = null;
    public ?string $fr_rpm = null;
    public ?string $fc_lpm = null;
    public ?string $pulso_ppm = null;
    public ?string $tlc_seg = null;
    public ?string $tpc_seg = null;
    public ?string $pas = null;
    public ?string $pad = null;
    public ?string $prcnt_deshidratacion = null;
    public ?string $gangliios_palpables = null;
    public ?string $mucosas_visibles = null;
    public ?string $ectoparasitos = null;
    public ?string $actitud = null;
    public ?string $hallazgos = null;

    public function obtenerExamen(int $id): ?array
    {
        $consulta = $this->getConexion()->prepare("SELECT * FROM examen_clinico WHERE id_consulta = :id");
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function agregarExamen(int $id, array $datos): bool
    {
        $columnas = ['peso','cc','temp_celsius','pulso_yugular','fr_rpm','fc_lpm','pulso_ppm','tlc_seg',
            'tpc_seg','pas','pad','prcnt_deshidratacion','gangliios_palpables','mucosas_visibles',
            'ectoparasitos','actitud','hallazgos'];
        $valores = [':id_consulta' => $id];
        $marcadores = [];
        foreach ($columnas as $columna) {
            $marcadores[] = ":$columna";
            $valores[":$columna"] = $datos[$columna] ?? null;
        }
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO examen_clinico (id_consulta, " . implode(', ', $columnas) . ")
             VALUES (:id_consulta, " . implode(', ', $marcadores) . ")"
        );
        return $consulta->execute($valores);
    }

    public function editarExamen(int $id, array $datos): bool
    {
        $columnas = ['peso','cc','temp_celsius','pulso_yugular','fr_rpm','fc_lpm','pulso_ppm','tlc_seg',
            'tpc_seg','pas','pad','prcnt_deshidratacion','gangliios_palpables','mucosas_visibles',
            'ectoparasitos','actitud','hallazgos'];
        $valores = [':id_consulta' => $id];
        $cambios = [];
        foreach ($columnas as $columna) {
            $cambios[] = "$columna = :$columna";
            $valores[":$columna"] = $datos[$columna] ?? null;
        }
        $consulta = $this->getConexion()->prepare(
            "UPDATE examen_clinico SET " . implode(', ', $cambios) . " WHERE id_consulta = :id_consulta"
        );
        return $consulta->execute($valores);
    }
}
