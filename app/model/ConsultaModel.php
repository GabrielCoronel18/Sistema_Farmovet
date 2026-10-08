<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;
use Throwable;

class ConsultaModel extends ConexionBD
{
    public function obtenerMascotasActivas(): array
    {
        $db = $this->getConexion();
        $sql = "SELECT m.id_mascota, m.nombre, c.nombre AS nombre_cliente
                FROM mascota m
                INNER JOIN cliente c ON c.cedula_cliente = m.cedula_cliente
                WHERE m.estado = 1
                ORDER BY m.nombre";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPatologiasActivas(): array
    {
        $db = $this->getConexion();
        return $db->query(
            "SELECT id_patologia, nombre FROM patologia WHERE estado = 1 ORDER BY nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMedicamentosActivos(): array
    {
        $db = $this->getConexion();
        return $db->query(
            "SELECT id_medicamento, nombre_medicamento
             FROM medicamento
             WHERE estado = 1
             ORDER BY nombre_medicamento"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarConsulta(
        array $consulta,
        array $anamnesis,
        array $examen,
        array $laboratorio,
        array $diagnosticos,
        array $recetas
    ): int {
        $db = $this->getConexion();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare(
                "INSERT INTO consulta
                    (cedula_usuario, id_mascota, fecha, tipo_ingreso, remitido, pronostico,
                     tratamiento_consulta, `peso_trat-consulta`, comentario_seguimientoo, estado)
                 VALUES
                    (:cedula_usuario, :id_mascota, :fecha, :tipo_ingreso, :remitido, :pronostico,
                     :tratamiento_consulta, :peso_consulta, :comentario, 1)"
            );
            $stmt->execute([
                ':cedula_usuario' => $consulta['cedula_usuario'],
                ':id_mascota' => $consulta['id_mascota'],
                ':fecha' => $consulta['fecha'],
                ':tipo_ingreso' => $consulta['tipo_ingreso'],
                ':remitido' => $consulta['remitido'],
                ':pronostico' => $consulta['pronostico'] ?? '',
                ':tratamiento_consulta' => $consulta['tratamiento_consulta'] ?? '',
                ':peso_consulta' => $consulta['peso_consulta'] ?? '',
                ':comentario' => $consulta['comentario'] ?? ''
            ]);
            $idConsulta = (int) $db->lastInsertId();
            $this->guardarSecciones($db, $idConsulta, $anamnesis, $examen, $laboratorio, $diagnosticos, $recetas);

            $db->commit();
            return $idConsulta;
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }

    public function actualizarConsulta(
        int $idConsulta,
        array $consulta,
        array $anamnesis,
        array $examen,
        array $laboratorio,
        array $diagnosticos,
        array $recetas
    ): void {
        $db = $this->getConexion();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare(
                "UPDATE consulta
                 SET id_mascota = :id_mascota, fecha = :fecha, tipo_ingreso = :tipo_ingreso,
                     remitido = :remitido, pronostico = :pronostico,
                     tratamiento_consulta = :tratamiento_consulta,
                     `peso_trat-consulta` = :peso_consulta,
                     comentario_seguimientoo = :comentario
                 WHERE id_consulta = :id_consulta AND estado = 1"
            );
            $stmt->execute([
                ':id_mascota' => $consulta['id_mascota'],
                ':fecha' => $consulta['fecha'],
                ':tipo_ingreso' => $consulta['tipo_ingreso'],
                ':remitido' => $consulta['remitido'],
                ':pronostico' => $consulta['pronostico'] ?? '',
                ':tratamiento_consulta' => $consulta['tratamiento_consulta'] ?? '',
                ':peso_consulta' => $consulta['peso_consulta'] ?? '',
                ':comentario' => $consulta['comentario'] ?? '',
                ':id_consulta' => $idConsulta
            ]);

            if ($stmt->rowCount() === 0) {
                $verificar = $db->prepare('SELECT 1 FROM consulta WHERE id_consulta = ? AND estado = 1');
                $verificar->execute([$idConsulta]);
                if (!$verificar->fetchColumn()) {
                    throw new \RuntimeException('La consulta que intenta actualizar no está disponible.');
                }
            }

            $this->guardarSecciones($db, $idConsulta, $anamnesis, $examen, $laboratorio, $diagnosticos, $recetas);
            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }

    public function obtenerConsultaParaEditar(int $idConsulta): ?array
    {
        $db = $this->getConexion();
        $stmt = $db->prepare(
            "SELECT id_consulta, id_mascota, fecha, tipo_ingreso, remitido, pronostico,
                    tratamiento_consulta, `peso_trat-consulta` AS peso_consulta,
                    comentario_seguimientoo AS comentario
             FROM consulta
             WHERE id_consulta = ? AND estado = 1"
        );
        $stmt->execute([$idConsulta]);
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$consulta) {
            return null;
        }

        return [
            'consulta' => $consulta,
            'detalle' => $this->obtenerDetalles([$idConsulta])[$idConsulta]
        ];
    }

    public function eliminarConsulta(int $idConsulta): bool
    {
        $db = $this->getConexion();
        $stmt = $db->prepare('UPDATE consulta SET estado = 0 WHERE id_consulta = ? AND estado = 1');
        $stmt->execute([$idConsulta]);
        return $stmt->rowCount() === 1;
    }

    public function obtenerConsultas(): array
    {
        $db = $this->getConexion();
        $sql = "SELECT c.id_consulta, c.id_mascota, c.fecha, c.tipo_ingreso, c.remitido,
                       c.pronostico, c.tratamiento_consulta, c.`peso_trat-consulta` AS peso_consulta,
                       c.comentario_seguimientoo AS comentario,
                       m.nombre AS nombre_mascota, cl.nombre AS nombre_cliente
                FROM consulta c
                INNER JOIN mascota m ON m.id_mascota = c.id_mascota
                INNER JOIN cliente cl ON cl.cedula_cliente = m.cedula_cliente
                WHERE c.estado = 1
                ORDER BY c.fecha DESC, c.id_consulta DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalles(array $idConsultas): array
    {
        $idConsultas = array_values(array_unique(array_map('intval', $idConsultas)));
        if (!$idConsultas) {
            return [];
        }

        $db = $this->getConexion();
        $anamnesis = (new AnamnesisModel())->obtenerPorConsultas($db, $idConsultas);
        $examenes = (new ExamenClinicoModel())->obtenerPorConsultas($db, $idConsultas);
        $laboratorios = (new ResultadosLaboratoriaModel())->obtenerPorConsultas($db, $idConsultas);
        $diagnosticos = (new DiagnosticoModel())->obtenerPorConsultas($db, $idConsultas);
        $recetas = (new RecipeModel())->obtenerPorConsultas($db, $idConsultas);

        $detalles = [];
        foreach ($idConsultas as $idConsulta) {
            $detalles[$idConsulta] = [
                'anamnesis' => $anamnesis[$idConsulta] ?? [],
                'examen' => $examenes[$idConsulta] ?? [],
                'laboratorio' => $laboratorios[$idConsulta] ?? [],
                'diagnosticos' => $diagnosticos[$idConsulta] ?? [],
                'recetas' => $recetas[$idConsulta] ?? []
            ];
        }

        return $detalles;
    }

    private function contieneDatos(array $datos): bool
    {
        foreach ($datos as $valor) {
            if ($valor !== null && trim((string) $valor) !== '') {
                return true;
            }
        }
        return false;
    }

    private function guardarSecciones(
        PDO $db,
        int $idConsulta,
        array $anamnesis,
        array $examen,
        array $laboratorio,
        array $diagnosticos,
        array $recetas
    ): void {
        $this->guardarSeccion($db, $idConsulta, $anamnesis, new AnamnesisModel());
        $this->guardarSeccion($db, $idConsulta, $examen, new ExamenClinicoModel());
        $this->guardarSeccion($db, $idConsulta, $laboratorio, new ResultadosLaboratoriaModel());
        $this->guardarColeccion(
            $db,
            $idConsulta,
            $diagnosticos,
            (new DiagnosticoModel())->obtenerPorConsulta($db, $idConsulta),
            new DiagnosticoModel(),
            'id_diagnostico'
        );
        $this->guardarColeccion(
            $db,
            $idConsulta,
            $recetas,
            (new RecipeModel())->obtenerPorConsulta($db, $idConsulta),
            new RecipeModel(),
            'id_recipe'
        );
    }

    private function guardarSeccion(
        PDO $db,
        int $idConsulta,
        array $datos,
        object $modelo
    ): void {
        $registro = $modelo->obtenerPorConsulta($db, $idConsulta);
        if (!$this->contieneDatos($datos)) {
            if ($registro) {
                $columnas = array_filter(
                    array_keys($registro),
                    static fn(string $columna): bool => !str_starts_with($columna, 'id_')
                );
                $modelo->actualizar($db, $idConsulta, array_fill_keys($columnas, null));
            }
            return;
        }

        if (!$registro) {
            $modelo->agregar($db, $idConsulta, $datos);
            return;
        }
        $modelo->actualizar($db, $idConsulta, $datos);
    }

    private function guardarColeccion(
        PDO $db,
        int $idConsulta,
        array $datosNuevos,
        array $datosExistentes,
        object $modelo,
        string $idCampo
    ): void {
        $existentes = [];
        foreach ($datosExistentes as $registro) {
            $existentes[(int) $registro[$idCampo]] = $registro;
        }

        $conservar = [];
        foreach ($datosNuevos as $datos) {
            $idRegistro = (int) ($datos[$idCampo] ?? 0);
            unset($datos[$idCampo]);
            if ($idRegistro > 0) {
                if (!isset($existentes[$idRegistro])) {
                    throw new \InvalidArgumentException('No se encontró uno de los detalles asociados a la consulta.');
                }
                $modelo->actualizar($db, $idRegistro, $idConsulta, $datos);
                $conservar[] = $idRegistro;
            } else {
                $modelo->agregar($db, $idConsulta, $datos);
            }
        }

        foreach ($existentes as $idRegistro => $_registro) {
            if (!in_array($idRegistro, $conservar, true)) {
                $modelo->eliminar($db, $idRegistro, $idConsulta);
            }
        }
    }
}
