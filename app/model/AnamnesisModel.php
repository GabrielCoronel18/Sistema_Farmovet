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
    public ?string $act_ectoparasitos = null;
    public ?string $ant_ectoparasitos = null;

    public function obtenerAnamnesis(int $id): ?array
    {
        $consulta = $this->getConexion()->prepare("SELECT * FROM anamnesis WHERE id_consulta = :id");
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function agregarAnamnesis(int $id, array $datos): bool
    {
        $columnas = ['motivo_consulta','inicio_enfermedad','examenes_efectuados','tratamientos_realizados',
            'ult_desp_interna','ult_desp_int_producto','ult_desp_externa','ult_desp_ext_producto',
            'ultima_vacunacion','tipo_alimentacion','frec_alimentacion','apetito','ingesta_agua',
            'contacto_animal','vomito','heces','miccion','fch_ultimo_celo','prod_higiene','frec_higiene',
            'int_ambiente','ext_ambiente','act_ectoparasitos','ant_ectoparasitos'];
        $valores = [];
        foreach ($columnas as $columna) {
            $valores[$columna] = $datos[$columna] ?? null;
        }
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO anamnesis (id_consulta, " . implode(', ', $columnas) . ")
             VALUES (:id_consulta, :" . implode(', :', $columnas) . ")"
        );
        $valores['id_consulta'] = $id;
        return $consulta->execute($valores);
    }

    public function editarAnamnesis(int $id, array $datos): bool
    {
        $columnas = ['motivo_consulta','inicio_enfermedad','examenes_efectuados','tratamientos_realizados',
            'ult_desp_interna','ult_desp_int_producto','ult_desp_externa','ult_desp_ext_producto',
            'ultima_vacunacion','tipo_alimentacion','frec_alimentacion','apetito','ingesta_agua',
            'contacto_animal','vomito','heces','miccion','fch_ultimo_celo','prod_higiene','frec_higiene',
            'int_ambiente','ext_ambiente','act_ectoparasitos','ant_ectoparasitos'];
        $valores = [];
        $cambios = [];
        foreach ($columnas as $columna) {
            $cambios[] = "$columna = :$columna";
            $valores[$columna] = $datos[$columna] ?? null;
        }
        $valores['id_consulta'] = $id;
        $consulta = $this->getConexion()->prepare(
            "UPDATE anamnesis SET " . implode(', ', $cambios) . " WHERE id_consulta = :id_consulta"
        );
        return $consulta->execute($valores);
    }
}
