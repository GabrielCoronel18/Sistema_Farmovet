<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;
use Throwable;

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

    public function agregar(): int
    {
        $stmt = $this->getConexion()->prepare(
            "INSERT INTO consulta
                (cedula_usuario, id_mascota, fecha, tipo_ingreso, remitido, pronostico,
                 tratamiento_consulta, `peso_trat-consulta`, comentario_seguimientoo, estado)
             VALUES
                (:cedula_usuario, :id_mascota, :fecha, :tipo_ingreso, :remitido, :pronostico,
                 :tratamiento_consulta, :peso_consulta, :comentario, 1)"
        );
        $stmt->execute([
            ':cedula_usuario' => $this->cedula_usuario,
            ':id_mascota' => $this->id_mascota,
            ':fecha' => $this->fecha,
            ':tipo_ingreso' => $this->tipo_ingreso,
            ':remitido' => $this->remitido,
            ':pronostico' => $this->pronostico,
            ':tratamiento_consulta' => $this->tratamiento_consulta,
            ':peso_consulta' => $this->peso_consulta,
            ':comentario' => $this->comentario
        ]);
        $this->id_consulta = (int) $this->getConexion()->lastInsertId();
        return $this->id_consulta;
    }

    public function obtener(int $idConsulta): ?array
    {
        $stmt = $this->getConexion()->prepare(
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
        $this->cargarConsulta($consulta);
        $this->id_consulta = $idConsulta;
        return $consulta;
    }

    public function actualizar(): bool
    {
        $db = $this->getConexion();
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
            ':id_mascota' => $this->id_mascota,
            ':fecha' => $this->fecha,
            ':tipo_ingreso' => $this->tipo_ingreso,
            ':remitido' => $this->remitido,
            ':pronostico' => $this->pronostico,
            ':tratamiento_consulta' => $this->tratamiento_consulta,
            ':peso_consulta' => $this->peso_consulta,
            ':comentario' => $this->comentario,
            ':id_consulta' => $this->id_consulta
        ]);
        if ($stmt->rowCount() !== 0) {
            return true;
        }
        $verificar = $db->prepare('SELECT 1 FROM consulta WHERE id_consulta = ? AND estado = 1');
        $verificar->execute([$this->id_consulta]);
        return (bool) $verificar->fetchColumn();
    }

    public function registrarConsulta(
        array $consulta,
        array $anamnesis,
        array $examen,
        array $laboratorio,
        array $diagnosticos,
        array $recetas
    ): int {
        $this->cargarConsulta($consulta);
        $db = $this->getConexion();
        $db->beginTransaction();

        try {
            $idConsulta = $this->agregar();
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
        $this->cargarConsulta($consulta);
        $this->id_consulta = $idConsulta;
        $db = $this->getConexion();
        $db->beginTransaction();

        try {
            if (!$this->actualizar()) {
                throw new \RuntimeException('La consulta que intenta actualizar no está disponible.');
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
        if (!$consulta = $this->obtener($idConsulta)) {
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
        $anamnesis = (new AnamnesisModel($db))->obtenerPorConsultas($idConsultas);
        $examenes = (new ExamenClinicoModel($db))->obtenerPorConsultas($idConsultas);
        $laboratorios = (new ResultadosLaboratoriaModel($db))->obtenerPorConsultas($idConsultas);
        $diagnosticos = (new DiagnosticoModel($db))->obtenerPorConsultas($idConsultas);
        $recetas = (new RecipeModel($db))->obtenerPorConsultas($idConsultas);

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
        $this->guardarSeccion($idConsulta, $anamnesis, new AnamnesisModel($db));
        $this->guardarSeccion($idConsulta, $examen, new ExamenClinicoModel($db));
        $this->guardarSeccion($idConsulta, $laboratorio, new ResultadosLaboratoriaModel($db));
        $this->guardarColeccion(
            $idConsulta,
            $diagnosticos,
            (new DiagnosticoModel($db))->obtenerPorConsulta($idConsulta),
            new DiagnosticoModel($db),
            'id_diagnostico'
        );
        $this->guardarColeccion(
            $idConsulta,
            $recetas,
            (new RecipeModel($db))->obtenerPorConsulta($idConsulta),
            new RecipeModel($db),
            'id_recipe'
        );
    }

    private function guardarSeccion(
        int $idConsulta,
        array $datos,
        object $modelo
    ): void {
        $modelo->id_consulta = $idConsulta;
        $registro = $modelo->obtenerPorConsulta($idConsulta);
        $modelo->cargarDatos($datos);
        if (!$this->contieneDatos($datos)) {
            if ($registro) {
                $modelo->limpiarCampos();
                $modelo->actualizar();
            }
            return;
        }

        if (!$registro) {
            $modelo->agregar();
            return;
        }
        $modelo->actualizar();
    }

    private function guardarColeccion(
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
            $modelo->cargarDatos($datos);
            $modelo->id_consulta = $idConsulta;
            if ($idRegistro > 0) {
                if (!isset($existentes[$idRegistro])) {
                    throw new \InvalidArgumentException('No se encontró uno de los detalles asociados a la consulta.');
                }
                $modelo->$idCampo = $idRegistro;
                $modelo->actualizar();
                $conservar[] = $idRegistro;
            } else {
                $modelo->agregar();
            }
        }

        foreach ($existentes as $idRegistro => $_registro) {
            if (!in_array($idRegistro, $conservar, true)) {
                $modelo->$idCampo = $idRegistro;
                $modelo->id_consulta = $idConsulta;
                $modelo->eliminar();
            }
        }
    }

    private function cargarConsulta(array $consulta): void
    {
        $this->cedula_usuario = $consulta['cedula_usuario'] ?? $this->cedula_usuario;
        $this->id_mascota = isset($consulta['id_mascota']) ? (int) $consulta['id_mascota'] : $this->id_mascota;
        $this->fecha = $consulta['fecha'] ?? $this->fecha;
        $this->tipo_ingreso = isset($consulta['tipo_ingreso']) ? (int) $consulta['tipo_ingreso'] : $this->tipo_ingreso;
        $this->remitido = isset($consulta['remitido']) ? (int) $consulta['remitido'] : $this->remitido;
        $this->pronostico = $consulta['pronostico'] ?? '';
        $this->tratamiento_consulta = $consulta['tratamiento_consulta'] ?? '';
        $this->peso_consulta = $consulta['peso_consulta'] ?? '';
        $this->comentario = $consulta['comentario'] ?? '';
    }
}
