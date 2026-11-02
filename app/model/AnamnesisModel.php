<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class AnamnesisModel extends ConexionBD
{
    private ?int $id_anamnesis = null;
    private ?int $id_consulta = null;
    private ?string $motivo_consulta = null;
    private ?string $inicio_enfermedad = null;
    private ?string $examenes_efectuados = null;
    private ?string $tratamientos_realizados = null;
    private ?string $ult_desp_interna = null;
    private ?string $ult_desp_int_producto = null;
    private ?string $ult_desp_externa = null;
    private ?string $ult_desp_ext_producto = null;
    private ?string $ultima_vacunacion = null;
    private ?string $tipo_alimentacion = null;
    private ?string $frec_alimentacion = null;
    private ?string $apetito = null;
    private ?string $ingesta_agua = null;
    private ?string $contacto_animal = null;
    private ?string $vomito = null;
    private ?string $heces = null;
    private ?string $miccion = null;
    private ?string $fch_ultimo_celo = null;
    private ?string $prod_higiene = null;
    private ?string $frec_higiene = null;
    private ?string $int_ambiente = null;
    private ?string $ext_ambiente = null;
    private ?string $act_ectoparasitos = null;
    private ?string $ant_ectoparasitos = null;

    private const COLUMNAS = [
        'motivo_consulta','inicio_enfermedad','examenes_efectuados','tratamientos_realizados',
        'ult_desp_interna','ult_desp_int_producto','ult_desp_externa','ult_desp_ext_producto',
        'ultima_vacunacion','tipo_alimentacion','frec_alimentacion','apetito','ingesta_agua',
        'contacto_animal','vomito','heces','miccion','fch_ultimo_celo','prod_higiene','frec_higiene',
        'int_ambiente','ext_ambiente','act_ectoparasitos','ant_ectoparasitos'
    ];

    private function hidratar(array $datos): void
    {
        foreach (self::COLUMNAS as $columna) {
            $this->{$columna} = $datos[$columna] ?? null;
        }
    }

    public function obtenerAnamnesis(int $id): ?array
    {
        $consulta = $this->getConexion()->prepare("SELECT * FROM anamnesis WHERE id_consulta = :id");
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function agregarAnamnesis(int $id, array $datos): bool
    {
        $this->id_consulta = $id;
        $this->hidratar($datos);
        $columnas = self::COLUMNAS;
        $valores = [];
        foreach ($columnas as $columna) {
            $valores[$columna] = $this->{$columna};
        }
        $consulta = $this->getConexion()->prepare(
            "INSERT INTO anamnesis (id_consulta, " . implode(', ', $columnas) . ")
             VALUES (:id_consulta, :" . implode(', :', $columnas) . ")"
        );
        $valores['id_consulta'] = $this->id_consulta;
        return $consulta->execute($valores);
    }

    public function actualizarAnamnesis(int $id, array $datos): bool
    {
        $this->id_consulta = $id;
        $this->hidratar($datos);
        $columnas = self::COLUMNAS;
        $valores = [];
        $cambios = [];
        foreach ($columnas as $columna) {
            $cambios[] = "$columna = :$columna";
            $valores[$columna] = $this->{$columna};
        }
        $valores['id_consulta'] = $this->id_consulta;
        $consulta = $this->getConexion()->prepare(
            "UPDATE anamnesis SET " . implode(', ', $cambios) . " WHERE id_consulta = :id_consulta"
        );
        if (!$consulta->execute($valores)) {
            return false;
        }
        $existencia = $this->getConexion()->prepare(
            "SELECT 1 FROM anamnesis WHERE id_consulta = :id_consulta"
        );
        $existencia->execute([':id_consulta' => $this->id_consulta]);
        if ($existencia->fetchColumn() !== false) {
            return true;
        }
        return $this->agregarAnamnesis($id, $datos);
    }
}
