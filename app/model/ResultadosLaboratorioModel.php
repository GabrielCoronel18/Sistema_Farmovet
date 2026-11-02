<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ResultadosLaboratorioModel extends ConexionBD
{
    private ?int $id_resultado = null;
    private ?int $id_consulta = null;
    private ?string $hematologia_completa = null;
    private ?string $coprologia = null;
    private ?string $quimica_sanguinea = null;
    private ?string $uro_sangre = null;
    private ?string $uro_urob = null;
    private ?string $uro_bli = null;
    private ?string $uro_prot = null;
    private ?string $uro_nitritos = null;
    private ?string $uro_cetona = null;
    private ?string $uro_glucosa = null;
    private ?string $uro_ph = null;
    private ?string $uro_leu = null;
    private ?string $uro_densidad = null;
    private ?string $uro_microorganismos = null;
    private ?string $uro_celulas = null;
    private ?string $uro_cilindros = null;
    private ?string $uro_cristales = null;
    private ?string $descarte = null;
    private ?string $piel_otros = null;
    private ?string $snap = null;
    private ?string $observaciones = null;

    private const COLUMNAS = [
        'hematologia_completa','coprologia','quimica_sanguinea','uro_sangre','uro_urob',
        'uro_bli','uro_prot','uro_nitritos','uro_cetona','uro_glucosa','uro_ph','uro_leu','uro_densidad',
        'uro_microorganismos','uro_celulas','uro_cilindros','uro_cristales','descarte','piel_otros',
        'snap','observaciones'
    ];

    private function hidratar(array $datos): void
    {
        foreach (self::COLUMNAS as $columna) {
            $this->{$columna} = $datos[$columna] ?? null;
        }
    }

    public function obtenerResultado(int $id): ?array
    {
        $consulta = $this->getConexion()->prepare("SELECT * FROM resultado_laboratorio WHERE id_consulta = :id");
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function agregarResultado(int $id, array $datos): bool
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
            "INSERT INTO resultado_laboratorio (id_consulta, " . implode(', ', $columnas) . ")
             VALUES (:id_consulta, " . implode(', ', $marcadores) . ")"
        );
        return $consulta->execute($valores);
    }

    public function actualizarResultado(int $id, array $datos): bool
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
            "UPDATE resultado_laboratorio SET " . implode(', ', $cambios) . " WHERE id_consulta = :id_consulta"
        );
        if (!$consulta->execute($valores)) {
            return false;
        }
        $existencia = $this->getConexion()->prepare(
            "SELECT 1 FROM resultado_laboratorio WHERE id_consulta = :id_consulta"
        );
        $existencia->execute([':id_consulta' => $this->id_consulta]);
        if ($existencia->fetchColumn() !== false) {
            return true;
        }
        return $this->agregarResultado($id, $datos);
    }
}
