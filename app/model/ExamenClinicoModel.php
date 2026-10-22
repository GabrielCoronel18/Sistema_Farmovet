<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ExamenClinicoModel extends ConexionBD
{
    public ?int $id_examen = null;
    public ?int $id_consulta = null;
    public ?string $peso = null;
    public ?int $cc = null;
    public ?string $temp_celsius = null;
    public ?string $pulso_yugular = null;
    public ?int $fr_rpm = null;
    public ?int $fc_lpm = null;
    public ?int $pulso_ppm = null;
    public ?int $tlc_seg = null;
    public ?int $tpc_seg = null;
    public ?string $pas = null;
    public ?string $pad = null;
    public ?string $prcnt_deshidratacion = null;
    public ?string $gangliios_palpables = null;
    public ?string $mucosas_visibles = null;
    public ?string $ectoparasitos = null;
    public ?string $actitud = null;
    public ?string $hallazgos = null;

    public function cargarDatos(array $datos): void
    {
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo) && $campo !== 'id_examen') {
                $this->$campo = $valor === '' ? null : $valor;
            }
        }
    }

    public function agregar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'INSERT INTO examen_clinico
                (id_consulta, peso, cc, temp_celsius, pulso_yugular, fr_rpm, fc_lpm, pulso_ppm, tlc_seg, tpc_seg,
                 pas, pad, prcnt_deshidratacion, gangliios_palpables, mucosas_visibles, ectoparasitos, actitud, hallazgos)
             VALUES
                (:id_consulta, :peso, :cc, :temp_celsius, :pulso_yugular, :fr_rpm, :fc_lpm, :pulso_ppm, :tlc_seg,
                 :tpc_seg, :pas, :pad, :prcnt_deshidratacion, :gangliios_palpables, :mucosas_visibles,
                 :ectoparasitos, :actitud, :hallazgos)'
        );
        $stmt->execute($this->valores());
        $this->id_examen = (int) $this->getConexion()->lastInsertId();
    }

    public function obtenerPorConsulta(int $idConsulta): ?array
    {
        $stmt = $this->getConexion()->prepare('SELECT * FROM examen_clinico WHERE id_consulta = ?');
        $stmt->execute([$idConsulta]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$registro) {
            return null;
        }
        $this->cargarDatos($registro);
        return $registro;
    }

    public function obtenerPorConsultas(array $idConsultas): array
    {
        if (!$idConsultas) {
            return [];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        $stmt = $this->getConexion()->prepare(
            "SELECT * FROM examen_clinico WHERE id_consulta IN ({$marcadores})"
        );
        $stmt->execute($idConsultas);
        $registros = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $registros[(int) $registro['id_consulta']] = $registro;
        }
        return $registros;
    }

    public function actualizar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'UPDATE examen_clinico SET peso = :peso, cc = :cc, temp_celsius = :temp_celsius,
             pulso_yugular = :pulso_yugular, fr_rpm = :fr_rpm, fc_lpm = :fc_lpm, pulso_ppm = :pulso_ppm,
             tlc_seg = :tlc_seg, tpc_seg = :tpc_seg, pas = :pas, pad = :pad,
             prcnt_deshidratacion = :prcnt_deshidratacion, gangliios_palpables = :gangliios_palpables,
             mucosas_visibles = :mucosas_visibles, ectoparasitos = :ectoparasitos,
             actitud = :actitud, hallazgos = :hallazgos
             WHERE id_consulta = :id_consulta'
        );
        $stmt->execute($this->valores());
    }

    public function limpiarCampos(): void
    {
        $this->peso = $this->temp_celsius = $this->pulso_yugular = null;
        $this->cc = $this->fr_rpm = $this->fc_lpm = $this->pulso_ppm = null;
        $this->tlc_seg = $this->tpc_seg = null;
        $this->pas = $this->pad = $this->prcnt_deshidratacion = null;
        $this->gangliios_palpables = $this->mucosas_visibles = $this->ectoparasitos = null;
        $this->actitud = $this->hallazgos = null;
    }

    private function valores(): array
    {
        return [
            ':id_consulta' => $this->id_consulta,
            ':peso' => $this->peso,
            ':cc' => $this->cc,
            ':temp_celsius' => $this->temp_celsius,
            ':pulso_yugular' => $this->pulso_yugular,
            ':fr_rpm' => $this->fr_rpm,
            ':fc_lpm' => $this->fc_lpm,
            ':pulso_ppm' => $this->pulso_ppm,
            ':tlc_seg' => $this->tlc_seg,
            ':tpc_seg' => $this->tpc_seg,
            ':pas' => $this->pas,
            ':pad' => $this->pad,
            ':prcnt_deshidratacion' => $this->prcnt_deshidratacion,
            ':gangliios_palpables' => $this->gangliios_palpables,
            ':mucosas_visibles' => $this->mucosas_visibles,
            ':ectoparasitos' => $this->ectoparasitos,
            ':actitud' => $this->actitud,
            ':hallazgos' => $this->hallazgos
        ];
    }
}
