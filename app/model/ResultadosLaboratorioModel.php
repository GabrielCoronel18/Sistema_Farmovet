<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ResultadosLaboratorioModel extends ConexionBD
{
    public ?int $id_resultado = null;
    public ?int $id_consulta = null;
    public ?string $hematologia_completa = null;
    public ?string $coprologia = null;
    public ?string $quimica_sanguinea = null;
    public ?string $uro_sangre = null;
    public ?string $uro_urob = null;
    public ?string $uro_bli = null;
    public ?string $uro_prot = null;
    public ?string $uro_nitritos = null;
    public ?string $uro_cetona = null;
    public ?string $uro_glucosa = null;
    public ?string $uro_ph = null;
    public ?string $uro_leu = null;
    public ?string $uro_densidad = null;
    public ?string $uro_microorganismos = null;
    public ?string $uro_celulas = null;
    public ?string $uro_cilindros = null;
    public ?string $uro_cristales = null;
    public ?string $descarte = null;
    public ?string $piel_otros = null;
    public ?string $snap = null;
    public ?string $observaciones = null;

    public function obtenerResultado(int $id): ?array
    {
        $consulta = $this->getConexion()->prepare("SELECT * FROM resultado_laboratorio WHERE id_consulta = :id");
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function agregarResultado(int $id, array $datos): bool
    {
        $columnas = ['hematologia_completa','coprologia','quimica_sanguinea','uro_sangre','uro_urob',
            'uro_bli','uro_prot','uro_nitritos','uro_cetona','uro_glucosa','uro_ph','uro_leu','uro_densidad',
            'uro_microorganismos','uro_celulas','uro_cilindros','uro_cristales','descarte','piel_otros',
            'snap','observaciones'];
        $valores = [':id_consulta' => $id];
        $marcadores = [];
        foreach ($columnas as $columna) {
            $marcadores[] = ":$columna";
            $valores[":$columna"] = $datos[$columna] ?? null;
        }
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO resultado_laboratorio (id_consulta, " . implode(', ', $columnas) . ")
             VALUES (:id_consulta, " . implode(', ', $marcadores) . ")"
        );
        return $consulta->execute($valores);
    }

    public function editarResultado(int $id, array $datos): bool
    {
        $columnas = ['hematologia_completa','coprologia','quimica_sanguinea','uro_sangre','uro_urob',
            'uro_bli','uro_prot','uro_nitritos','uro_cetona','uro_glucosa','uro_ph','uro_leu','uro_densidad',
            'uro_microorganismos','uro_celulas','uro_cilindros','uro_cristales','descarte','piel_otros',
            'snap','observaciones'];
        $valores = [':id_consulta' => $id];
        $cambios = [];
        foreach ($columnas as $columna) {
            $cambios[] = "$columna = :$columna";
            $valores[":$columna"] = $datos[$columna] ?? null;
        }
        $consulta = $this->getConexion()->prepare(
            "UPDATE resultado_laboratorio SET " . implode(', ', $cambios) . " WHERE id_consulta = :id_consulta"
        );
        return $consulta->execute($valores);
    }
}
