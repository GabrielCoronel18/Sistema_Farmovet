<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ResultadosLaboratoriaModel extends ConexionBD
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

    public function cargarDatos(array $datos): void
    {
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo) && $campo !== 'id_resultado') {
                $this->$campo = $valor === '' ? null : $valor;
            }
        }
    }

    public function agregar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'INSERT INTO resultado_laboratorio
                (id_consulta, hematologia_completa, coprologia, quimica_sanguinea, uro_sangre, uro_urob, uro_bli,
                 uro_prot, uro_nitritos, uro_cetona, uro_glucosa, uro_ph, uro_leu, uro_densidad,
                 uro_microorganismos, uro_celulas, uro_cilindros, uro_cristales, descarte, piel_otros, snap,
                 observaciones)
             VALUES
                (:id_consulta, :hematologia_completa, :coprologia, :quimica_sanguinea, :uro_sangre, :uro_urob,
                 :uro_bli, :uro_prot, :uro_nitritos, :uro_cetona, :uro_glucosa, :uro_ph, :uro_leu, :uro_densidad,
                 :uro_microorganismos, :uro_celulas, :uro_cilindros, :uro_cristales, :descarte, :piel_otros,
                 :snap, :observaciones)'
        );
        $stmt->execute($this->valores());
        $this->id_resultado = (int) $this->getConexion()->lastInsertId();
    }

    public function obtenerPorConsulta(int $idConsulta): ?array
    {
        $stmt = $this->getConexion()->prepare(
            'SELECT * FROM resultado_laboratorio WHERE id_consulta = ?'
        );
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
            "SELECT * FROM resultado_laboratorio WHERE id_consulta IN ({$marcadores})"
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
            'UPDATE resultado_laboratorio SET hematologia_completa = :hematologia_completa,
             coprologia = :coprologia, quimica_sanguinea = :quimica_sanguinea, uro_sangre = :uro_sangre,
             uro_urob = :uro_urob, uro_bli = :uro_bli, uro_prot = :uro_prot, uro_nitritos = :uro_nitritos,
             uro_cetona = :uro_cetona, uro_glucosa = :uro_glucosa, uro_ph = :uro_ph, uro_leu = :uro_leu,
             uro_densidad = :uro_densidad, uro_microorganismos = :uro_microorganismos, uro_celulas = :uro_celulas,
             uro_cilindros = :uro_cilindros, uro_cristales = :uro_cristales, descarte = :descarte,
             piel_otros = :piel_otros, snap = :snap, observaciones = :observaciones
             WHERE id_consulta = :id_consulta'
        );
        $stmt->execute($this->valores());
    }

    public function limpiarCampos(): void
    {
        $this->hematologia_completa = $this->coprologia = $this->quimica_sanguinea = null;
        $this->uro_sangre = $this->uro_urob = $this->uro_bli = $this->uro_prot = null;
        $this->uro_nitritos = $this->uro_cetona = $this->uro_glucosa = $this->uro_ph = null;
        $this->uro_leu = $this->uro_densidad = $this->uro_microorganismos = $this->uro_celulas = null;
        $this->uro_cilindros = $this->uro_cristales = $this->descarte = $this->piel_otros = null;
        $this->snap = $this->observaciones = null;
    }

    private function valores(): array
    {
        return [
            ':id_consulta' => $this->id_consulta,
            ':hematologia_completa' => $this->hematologia_completa,
            ':coprologia' => $this->coprologia,
            ':quimica_sanguinea' => $this->quimica_sanguinea,
            ':uro_sangre' => $this->uro_sangre,
            ':uro_urob' => $this->uro_urob,
            ':uro_bli' => $this->uro_bli,
            ':uro_prot' => $this->uro_prot,
            ':uro_nitritos' => $this->uro_nitritos,
            ':uro_cetona' => $this->uro_cetona,
            ':uro_glucosa' => $this->uro_glucosa,
            ':uro_ph' => $this->uro_ph,
            ':uro_leu' => $this->uro_leu,
            ':uro_densidad' => $this->uro_densidad,
            ':uro_microorganismos' => $this->uro_microorganismos,
            ':uro_celulas' => $this->uro_celulas,
            ':uro_cilindros' => $this->uro_cilindros,
            ':uro_cristales' => $this->uro_cristales,
            ':descarte' => $this->descarte,
            ':piel_otros' => $this->piel_otros,
            ':snap' => $this->snap,
            ':observaciones' => $this->observaciones
        ];
    }
}
