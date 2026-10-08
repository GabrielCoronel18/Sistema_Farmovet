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
        $detalles = [];
        foreach ($idConsultas as $idConsulta) {
            $detalles[$idConsulta] = [
                'anamnesis' => [],
                'examen' => [],
                'laboratorio' => [],
                'diagnosticos' => [],
                'recetas' => []
            ];
        }
        $marcadores = implode(', ', array_fill(0, count($idConsultas), '?'));
        foreach ([
            'anamnesis' => "SELECT * FROM anamnesis WHERE id_consulta IN ({$marcadores})",
            'examen' => "SELECT * FROM examen_clinico WHERE id_consulta IN ({$marcadores})",
            'laboratorio' => "SELECT * FROM resultado_laboratorio WHERE id_consulta IN ({$marcadores})"
        ] as $clave => $sql) {
            $stmt = $db->prepare($sql);
            $stmt->execute($idConsultas);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
                $detalles[(int) $registro['id_consulta']][$clave] = $registro;
            }
        }

        $stmt = $db->prepare(
            "SELECT d.id_consulta, d.id_patologia, d.tipo_diagnostico, p.nombre AS patologia
             FROM diagnostico d
             INNER JOIN patologia p ON p.id_patologia = d.id_patologia
             WHERE d.id_consulta IN ({$marcadores})
             ORDER BY d.id_diagnostico"
        );
        $stmt->execute($idConsultas);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $idConsulta = (int) $registro['id_consulta'];
            unset($registro['id_consulta']);
            $detalles[$idConsulta]['diagnosticos'][] = $registro;
        }

        $stmt = $db->prepare(
            "SELECT r.id_consulta, r.id_medicamento, m.nombre_medicamento, r.dosis, r.frecuencia, r.duracion
             FROM recipe r
             INNER JOIN medicamento m ON m.id_medicamento = r.id_medicamento
             WHERE r.id_consulta IN ({$marcadores})
             ORDER BY r.id_recipe"
        );
        $stmt->execute($idConsultas);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $idConsulta = (int) $registro['id_consulta'];
            unset($registro['id_consulta']);
            $detalles[$idConsulta]['recetas'][] = $registro;
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
        $this->guardarSeccion(
            $db,
            $idConsulta,
            'anamnesis',
            $anamnesis,
            [
                'motivo_consulta', 'inicio_enfermedad', 'examenes_efectuados', 'tratamientos_realizados',
                'ult_desp_interna', 'ult_desp_int_producto', 'ult_desp_externa', 'ult_desp_ext_producto',
                'ultima_vacunacion', 'tipo_alimentacion', 'frec_alimentacion', 'apetito', 'ingesta_agua',
                'contacto_animal', 'vomito', 'heces', 'miccion', 'fch_ultimo_celo', 'prod_higiene',
                'frec_higiene', 'int_ambiente', 'ext_ambiente', 'act_ectoparasitos', 'ant_ectoparasitos'
            ],
            AnamnesisModel::class
        );
        $this->guardarSeccion(
            $db,
            $idConsulta,
            'examen_clinico',
            $examen,
            [
                'peso', 'cc', 'temp_celsius', 'pulso_yugular', 'fr_rpm', 'fc_lpm', 'pulso_ppm',
                'tlc_seg', 'tpc_seg', 'pas', 'pad', 'prcnt_deshidratacion', 'gangliios_palpables',
                'mucosas_visibles', 'ectoparasitos', 'actitud', 'hallazgos'
            ],
            ExamenClinicoModel::class
        );
        $this->guardarSeccion(
            $db,
            $idConsulta,
            'resultado_laboratorio',
            $laboratorio,
            [
                'hematologia_completa', 'coprologia', 'quimica_sanguinea', 'uro_sangre', 'uro_urob',
                'uro_bli', 'uro_prot', 'uro_nitritos', 'uro_cetona', 'uro_glucosa', 'uro_ph', 'uro_leu',
                'uro_densidad', 'uro_microorganismos', 'uro_celulas', 'uro_cilindros', 'uro_cristales',
                'descarte', 'piel_otros', 'snap', 'observaciones'
            ],
            ResultadosLaboratoriaModel::class
        );

        foreach (['diagnostico', 'recipe'] as $tabla) {
            $stmt = $db->prepare("DELETE FROM {$tabla} WHERE id_consulta = ?");
            $stmt->execute([$idConsulta]);
        }
        foreach ($diagnosticos as $diagnostico) {
            (new DiagnosticoModel())->registrar($db, $idConsulta, $diagnostico);
        }
        foreach ($recetas as $receta) {
            (new RecipeModel())->registrar($db, $idConsulta, $receta);
        }
    }

    private function guardarSeccion(
        PDO $db,
        int $idConsulta,
        string $tabla,
        array $datos,
        array $columnas,
        string $modelo
    ): void {
        $stmt = $db->prepare("SELECT 1 FROM {$tabla} WHERE id_consulta = ?");
        $stmt->execute([$idConsulta]);
        $existe = (bool) $stmt->fetchColumn();

        if (!$this->contieneDatos($datos)) {
            if ($existe) {
                $stmt = $db->prepare("DELETE FROM {$tabla} WHERE id_consulta = ?");
                $stmt->execute([$idConsulta]);
            }
            return;
        }

        if (!$existe) {
            (new $modelo())->registrar($db, $idConsulta, $datos);
            return;
        }

        $asignaciones = implode(', ', array_map(static fn(string $columna): string => "{$columna} = ?", $columnas));
        $valores = array_map(static fn(string $columna) => $datos[$columna] ?? null, $columnas);
        $stmt = $db->prepare("UPDATE {$tabla} SET {$asignaciones} WHERE id_consulta = ?");
        $stmt->execute(array_merge($valores, [$idConsulta]));
    }
}
