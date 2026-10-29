<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class ConsultaModel extends ConexionBD
{
    public ?int $id_consulta = null;
    public ?string $cedula_usuario = null;
    public ?int $id_mascota = null;
    public ?string $fecha = null;
    public ?int $tipo_ingreso = null;
    public ?int $remitido = null;
    public ?string $pronostico = null;
    public ?string $tratamiento_consulta = null;
    public ?string $peso_consulta = null;
    public ?string $comentario = null;
    public ?int $estado = null;

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
                ':cedula_usuario' => $datos['cedula_usuario'],
                ':id_mascota' => $datos['id_mascota'],
                ':fecha' => $datos['fecha'],
                ':tipo_ingreso' => $datos['tipo_ingreso'],
                ':remitido' => $datos['remitido'],
                ':pronostico' => $datos['pronostico'],
                ':tratamiento' => $datos['tratamiento_consulta'],
                ':peso' => $datos['peso_consulta'],
                ':comentario' => $datos['comentario']
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

    public function editarConsulta(int $id, array $datos, array $anamnesis, array $examen,
        array $laboratorio, array $diagnosticos, array $recetas): bool
    {
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
                ':id_mascota' => $datos['id_mascota'],
                ':fecha' => $datos['fecha'],
                ':tipo_ingreso' => $datos['tipo_ingreso'],
                ':remitido' => $datos['remitido'],
                ':pronostico' => $datos['pronostico'],
                ':tratamiento' => $datos['tratamiento_consulta'],
                ':peso' => $datos['peso_consulta'],
                ':comentario' => $datos['comentario']
            ]);
            (new AnamnesisModel($conexion))->editarAnamnesis($id, $anamnesis);
            (new ExamenClinicoModel($conexion))->editarExamen($id, $examen);
            (new ResultadosLaboratorioModel($conexion))->editarResultado($id, $laboratorio);
            foreach ($diagnosticos as $diagnostico) {
                if (($diagnostico['id_diagnostico'] ?? 0) > 0) {
                    (new DiagnosticoModel($conexion))->editarDiagnostico($diagnostico['id_diagnostico'], $diagnostico);
                } else {
                    (new DiagnosticoModel($conexion))->agregarDiagnostico($id, $diagnostico);
                }
            }
            foreach ($recetas as $receta) {
                if (($receta['id_recipe'] ?? 0) > 0) {
                    (new RecipeModel($conexion))->editarRecipe($receta['id_recipe'], $receta);
                } else {
                    (new RecipeModel($conexion))->agregarRecipe($id, $receta);
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
