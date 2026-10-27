<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class AnamnesisModel extends ConexionBD
{
    public ?int $id_anamnesis = null;
    public ?int $id_consulta = null;
    public ?string $motivo_consulta = null;
    public ?string $inicio_enfermedad = null;
    public ?string $examenes_efectuados = null;
    public ?string $tratamientos_realizados = null;
    public ?string $ult_desp_interna = null;
    public ?string $ult_desp_int_producto = null;
    public ?string $ult_desp_externa = null;
    public ?string $ult_desp_ext_producto = null;
    public ?string $ultima_vacunacion = null;
    public ?string $tipo_alimentacion = null;
    public ?string $frec_alimentacion = null;
    public ?string $apetito = null;
    public ?string $ingesta_agua = null;
    public ?string $contacto_animal = null;
    public ?string $vomito = null;
    public ?string $heces = null;
    public ?string $miccion = null;
    public ?string $fch_ultimo_celo = null;
    public ?string $prod_higiene = null;
    public ?string $frec_higiene = null;
    public ?string $int_ambiente = null;
    public ?string $ext_ambiente = null;
    public ?int $act_ectoparasitos = null;
    public ?int $ant_ectoparasitos = null;

    public function cargarDatos(array $datos): void
    {
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo) && $campo !== 'id_anamnesis') {
                $this->$campo = $valor === '' ? null : $valor;
            }
        }
    }

    public function agregar(): void
    {
        $stmt = $this->getConexion()->prepare(
            'INSERT INTO anamnesis
                (id_consulta, motivo_consulta, inicio_enfermedad, examenes_efectuados, tratamientos_realizados,
                 ult_desp_interna, ult_desp_int_producto, ult_desp_externa, ult_desp_ext_producto,
                 ultima_vacunacion, tipo_alimentacion, frec_alimentacion, apetito, ingesta_agua,
                 contacto_animal, vomito, heces, miccion, fch_ultimo_celo, prod_higiene, frec_higiene,
                 int_ambiente, ext_ambiente, act_ectoparasitos, ant_ectoparasitos)
             VALUES
                (:id_consulta, :motivo_consulta, :inicio_enfermedad, :examenes_efectuados, :tratamientos_realizados,
                 :ult_desp_interna, :ult_desp_int_producto, :ult_desp_externa, :ult_desp_ext_producto,
                 :ultima_vacunacion, :tipo_alimentacion, :frec_alimentacion, :apetito, :ingesta_agua,
                 :contacto_animal, :vomito, :heces, :miccion, :fch_ultimo_celo, :prod_higiene, :frec_higiene,
                 :int_ambiente, :ext_ambiente, :act_ectoparasitos, :ant_ectoparasitos)'
        );
        $stmt->execute($this->valores());
        $this->id_anamnesis = (int) $this->getConexion()->lastInsertId();
    }

    public function obtenerPorConsulta(int $idConsulta): ?array
    {
        $stmt = $this->getConexion()->prepare('SELECT * FROM anamnesis WHERE id_consulta = ?');
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
            "SELECT * FROM anamnesis WHERE id_consulta IN ({$marcadores})"
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
            'UPDATE anamnesis SET motivo_consulta = :motivo_consulta, inicio_enfermedad = :inicio_enfermedad,
             examenes_efectuados = :examenes_efectuados, tratamientos_realizados = :tratamientos_realizados,
             ult_desp_interna = :ult_desp_interna, ult_desp_int_producto = :ult_desp_int_producto,
             ult_desp_externa = :ult_desp_externa, ult_desp_ext_producto = :ult_desp_ext_producto,
             ultima_vacunacion = :ultima_vacunacion, tipo_alimentacion = :tipo_alimentacion,
             frec_alimentacion = :frec_alimentacion, apetito = :apetito, ingesta_agua = :ingesta_agua,
             contacto_animal = :contacto_animal, vomito = :vomito, heces = :heces, miccion = :miccion,
             fch_ultimo_celo = :fch_ultimo_celo, prod_higiene = :prod_higiene, frec_higiene = :frec_higiene,
             int_ambiente = :int_ambiente, ext_ambiente = :ext_ambiente,
             act_ectoparasitos = :act_ectoparasitos, ant_ectoparasitos = :ant_ectoparasitos
             WHERE id_consulta = :id_consulta'
        );
        $stmt->execute($this->valores());
    }

    public function limpiarCampos(): void
    {
        $this->motivo_consulta = $this->inicio_enfermedad = $this->examenes_efectuados = null;
        $this->tratamientos_realizados = $this->ult_desp_interna = $this->ult_desp_int_producto = null;
        $this->ult_desp_externa = $this->ult_desp_ext_producto = $this->ultima_vacunacion = null;
        $this->tipo_alimentacion = $this->frec_alimentacion = $this->apetito = $this->ingesta_agua = null;
        $this->contacto_animal = $this->vomito = $this->heces = $this->miccion = null;
        $this->fch_ultimo_celo = $this->prod_higiene = $this->frec_higiene = null;
        $this->int_ambiente = $this->ext_ambiente = null;
        $this->act_ectoparasitos = $this->ant_ectoparasitos = null;
    }

    private function valores(): array
    {
        return [
            ':id_consulta' => $this->id_consulta,
            ':motivo_consulta' => $this->motivo_consulta,
            ':inicio_enfermedad' => $this->inicio_enfermedad,
            ':examenes_efectuados' => $this->examenes_efectuados,
            ':tratamientos_realizados' => $this->tratamientos_realizados,
            ':ult_desp_interna' => $this->ult_desp_interna,
            ':ult_desp_int_producto' => $this->ult_desp_int_producto,
            ':ult_desp_externa' => $this->ult_desp_externa,
            ':ult_desp_ext_producto' => $this->ult_desp_ext_producto,
            ':ultima_vacunacion' => $this->ultima_vacunacion,
            ':tipo_alimentacion' => $this->tipo_alimentacion,
            ':frec_alimentacion' => $this->frec_alimentacion,
            ':apetito' => $this->apetito,
            ':ingesta_agua' => $this->ingesta_agua,
            ':contacto_animal' => $this->contacto_animal,
            ':vomito' => $this->vomito,
            ':heces' => $this->heces,
            ':miccion' => $this->miccion,
            ':fch_ultimo_celo' => $this->fch_ultimo_celo,
            ':prod_higiene' => $this->prod_higiene,
            ':frec_higiene' => $this->frec_higiene,
            ':int_ambiente' => $this->int_ambiente,
            ':ext_ambiente' => $this->ext_ambiente,
            ':act_ectoparasitos' => $this->act_ectoparasitos,
            ':ant_ectoparasitos' => $this->ant_ectoparasitos
        ];
    }
}
