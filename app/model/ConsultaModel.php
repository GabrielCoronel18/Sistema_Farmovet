<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ConsultaModel extends ConexionBD
{
    private ?int $id_consulta = null;
    private ?string $cedula_usuario = null;
    private ?int $id_mascota = null;
    private ?string $fecha = null;
    private ?int $tipo_ingreso = null;
    private ?int $remitido = null;
    private ?string $pronostico = null;
    private ?string $tratamiento_consulta = null;
    private ?string $peso_consulta = null;
    private ?string $comentario = null;
    private ?int $estado = null;

    private function hidratar(array $datos): void
    {
        $this->cedula_usuario = (string) ($datos['cedula_usuario'] ?? '');
        $this->id_mascota = isset($datos['id_mascota']) ? (int) $datos['id_mascota'] : null;
        $this->fecha = isset($datos['fecha']) ? (string) $datos['fecha'] : null;
        $this->tipo_ingreso = isset($datos['tipo_ingreso']) ? (int) $datos['tipo_ingreso'] : null;
        $this->remitido = isset($datos['remitido']) ? (int) $datos['remitido'] : null;
        $this->pronostico = $datos['pronostico'] ?? null;
        $this->tratamiento_consulta = $datos['tratamiento_consulta'] ?? null;
        $this->peso_consulta = $datos['peso_consulta'] ?? null;
        $this->comentario = $datos['comentario'] ?? null;
    }

    public function obtenerConsultas(): array
    {
        $consulta = $this->getConexion()->query(
            "SELECT consulta.id_consulta, consulta.fecha, consulta.tipo_ingreso, consulta.remitido,
                    consulta.pronostico, consulta.`tratamiento_consulta`,
                    consulta.`peso_trat-consulta` AS peso_consulta,
                    consulta.comentario_seguimientoo AS comentario,
                    mascota.nombre AS nombre_mascota,
                    CONCAT(cliente.nombre, ' ', cliente.apellido) AS nombre_cliente
             FROM consulta
             INNER JOIN mascota ON mascota.id_mascota = consulta.id_mascota
             INNER JOIN cliente ON cliente.cedula_cliente = mascota.cedula_cliente
             WHERE consulta.estado = 1
             ORDER BY consulta.fecha DESC, consulta.id_consulta DESC"
        );
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerConsultaPorId(int $id): ?array
    {
        $consulta = $this->getConexion()->prepare(
            "SELECT consulta.*, consulta.`peso_trat-consulta` AS peso_consulta,
                    consulta.comentario_seguimientoo AS comentario,
                    mascota.nombre AS nombre_mascota,
                    CONCAT(cliente.nombre, ' ', cliente.apellido) AS nombre_cliente
             FROM consulta
             INNER JOIN mascota ON mascota.id_mascota = consulta.id_mascota
             INNER JOIN cliente ON cliente.cedula_cliente = mascota.cedula_cliente
             WHERE consulta.id_consulta = :id AND consulta.estado = 1"
        );
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function obtenerMascotasActivas(): array
    {
        $consulta = $this->getConexion()->query(
            "SELECT mascota.id_mascota, mascota.nombre,
                    CONCAT(cliente.nombre, ' ', cliente.apellido) AS nombre_cliente
             FROM mascota
             INNER JOIN cliente ON cliente.cedula_cliente = mascota.cedula_cliente
             WHERE mascota.estado = 1
             ORDER BY mascota.nombre"
        );
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPatologiasActivas(): array
    {
        $consulta = $this->getConexion()->query(
            "SELECT id_patologia, nombre FROM patologia
             WHERE estado = 1 ORDER BY nombre"
        );
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMedicamentosActivos(): array
    {
        $consulta = $this->getConexion()->query(
            "SELECT id_medicamento, nombre_medicamento FROM medicamento
             WHERE estado = 1 ORDER BY nombre_medicamento"
        );
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarConsulta(array $datos, array $anamnesis, array $examen,
        array $laboratorio, array $diagnosticos, array $recetas): int|false
    {
        $this->hidratar($datos);
        $conexion = $this->getConexion();
        $conexion->beginTransaction();
        try {
            $consulta = $conexion->prepare(
                "INSERT INTO consulta
                 (cedula_usuario, id_mascota, fecha, tipo_ingreso, remitido, pronostico,
                  tratamiento_consulta, `peso_trat-consulta`, comentario_seguimientoo, estado)
                 VALUES (:cedula_usuario, :id_mascota, :fecha, :tipo_ingreso, :remitido,
                         :pronostico, :tratamiento, :peso, :comentario, 1)"
            );
            $consulta->execute([
                ':cedula_usuario' => $this->cedula_usuario,
                ':id_mascota' => $this->id_mascota,
                ':fecha' => $this->fecha,
                ':tipo_ingreso' => $this->tipo_ingreso,
                ':remitido' => $this->remitido,
                ':pronostico' => $this->pronostico,
                ':tratamiento' => $this->tratamiento_consulta,
                ':peso' => $this->peso_consulta,
                ':comentario' => $this->comentario
            ]);
            $id = (int) $conexion->lastInsertId();
            (new AnamnesisModel($conexion))->agregarAnamnesis($id, $anamnesis);
            (new ExamenClinicoModel($conexion))->agregarExamen($id, $examen);
            (new ResultadosLaboratorioModel($conexion))->agregarResultado($id, $laboratorio);
            foreach ($diagnosticos as $diagnostico) {
                (new DiagnosticoModel($conexion))->agregarDiagnostico($id, $diagnostico);
            }
            foreach ($recetas as $receta) {
                (new RecipeModel($conexion))->agregarRecipe($id, $receta);
            }
            $conexion->commit();
            return $id;
        } catch (\PDOException $error) {
            $conexion->rollBack();
            return false;
        }
    }

    public function actualizarConsulta(int $id, array $datos, array $anamnesis, array $examen,
        array $laboratorio, array $diagnosticos, array $recetas): bool
    {
        $this->id_consulta = $id;
        $this->hidratar($datos);
        $conexion = $this->getConexion();
        $conexion->beginTransaction();
        try {
            $consulta = $conexion->prepare(
                "UPDATE consulta SET id_mascota = :id_mascota, fecha = :fecha,
                 tipo_ingreso = :tipo_ingreso, remitido = :remitido, pronostico = :pronostico,
                 tratamiento_consulta = :tratamiento, `peso_trat-consulta` = :peso,
                 comentario_seguimientoo = :comentario
                 WHERE id_consulta = :id AND estado = 1"
            );
            $consulta->execute([
                ':id' => $id,
                ':id_mascota' => $this->id_mascota,
                ':fecha' => $this->fecha,
                ':tipo_ingreso' => $this->tipo_ingreso,
                ':remitido' => $this->remitido,
                ':pronostico' => $this->pronostico,
                ':tratamiento' => $this->tratamiento_consulta,
                ':peso' => $this->peso_consulta,
                ':comentario' => $this->comentario
            ]);
            if (!(new AnamnesisModel($conexion))->actualizarAnamnesis($id, $anamnesis)) {
                throw new \PDOException('No fue posible actualizar la anamnesis.');
            }
            if (!(new ExamenClinicoModel($conexion))->actualizarExamen($id, $examen)) {
                throw new \PDOException('No fue posible actualizar el examen clínico.');
            }
            if (!(new ResultadosLaboratorioModel($conexion))->actualizarResultado($id, $laboratorio)) {
                throw new \PDOException('No fue posible actualizar los resultados de laboratorio.');
            }
            foreach ($diagnosticos as $diagnostico) {
                if (($diagnostico['id_diagnostico'] ?? 0) > 0) {
                    $actualizado = (new DiagnosticoModel($conexion))
                        ->actualizarDiagnostico($diagnostico['id_diagnostico'], $diagnostico);
                } else {
                    $actualizado = (new DiagnosticoModel($conexion))->agregarDiagnostico($id, $diagnostico);
                }
                if (!$actualizado) {
                    throw new \PDOException('No fue posible actualizar los diagnósticos.');
                }
            }
            foreach ($recetas as $receta) {
                if (($receta['id_recipe'] ?? 0) > 0) {
                    $actualizado = (new RecipeModel($conexion))
                        ->actualizarRecipe($receta['id_recipe'], $receta);
                } else {
                    $actualizado = (new RecipeModel($conexion))->agregarRecipe($id, $receta);
                }
                if (!$actualizado) {
                    throw new \PDOException('No fue posible actualizar las recetas.');
                }
            }
            $conexion->commit();
            return true;
        } catch (\PDOException $error) {
            $conexion->rollBack();
            return false;
        }
    }

    public function eliminarConsulta(int $id): bool
    {
        $conexion = $this->getConexion();
        $conexion->beginTransaction();
        try {
            $consulta = $conexion->prepare(
                "UPDATE consulta SET estado = 0 WHERE id_consulta = :id AND estado = 1"
            );
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->rowCount() > 0;
            $conexion->commit();
            return $resultado;
        } catch (\PDOException $error) {
            $conexion->rollBack();
            return false;
        }
    }
}
