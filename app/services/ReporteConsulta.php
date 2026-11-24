<?php
namespace Gabriel\SistemaFarmovet\services;

use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteConsulta
{
    private Dompdf $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $this->dompdf = new Dompdf($options);
    }

    private function esc(mixed $valor): string
    {
        if (is_array($valor)) {
            return htmlspecialchars(implode(', ', array_map('strval', $valor)), ENT_QUOTES, 'UTF-8');
        }
        return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
    }

    private function check(mixed $valor): string
    {
        return !empty($valor) ? 'X' : '&nbsp;';
    }

    private function tieneValor(mixed $valor): bool
    {
        return trim((string) ($valor ?? '')) !== '';
    }

    private function combinar(mixed ...$valores): string
    {
        $valores = array_filter(
            array_map(static fn ($valor): string => trim((string) ($valor ?? '')), $valores),
            static fn (string $valor): bool => $valor !== ''
        );

        return implode(' - ', $valores);
    }

    private function textoSiNo(mixed $valor): string
    {
        if ($valor === null || $valor === '') {
            return '';
        }

        return (int) $valor === 1 ? 'Sí' : 'No';
    }

    /**
     * Clasifica los diagnósticos por tipo:
     * - tentativo (dx tentativo / tentativo)
     * - diferencial (dx diferencial / dx diferenciales / diferencial / diferenciales)
     * - definitivo (definitivo / dx definitivo / diagnostico definitivo)
     */
    private function clasificarDiagnosticos(array $diagnosticos): array
    {
        $grupos = [
            'tentativo'   => [],
            'diferencial' => [],
            'definitivo'  => [],
        ];

        foreach ($diagnosticos as $item) {
            if (!is_array($item)) {
                continue;
            }

            $tipoRaw = mb_strtolower(trim((string) ($item['tipo_diagnostico'] ?? $item['tipo'] ?? '')), 'UTF-8');
            $patologia = trim((string) ($item['patologia'] ?? $item['diagnostico'] ?? $item['nombre'] ?? ''));
            $detalle = trim((string) ($item['observacion'] ?? $item['descripcion'] ?? ''));

            $texto = $patologia;
            if ($detalle !== '') {
                $texto .= $texto !== '' ? " ({$detalle})" : $detalle;
            }

            if ($texto === '') {
                continue;
            }

            if (str_contains($tipoRaw, 'tentat')) {
                $grupos['tentativo'][] = $texto;
            } elseif (str_contains($tipoRaw, 'diferenc')) {
                $grupos['diferencial'][] = $texto;
            } elseif (str_contains($tipoRaw, 'definit')) {
                $grupos['definitivo'][] = $texto;
            } else {
                $grupos['tentativo'][] = $texto;
            }
        }

        return [
            'tentativo'   => implode('; ', $grupos['tentativo']),
            'diferencial' => implode('; ', $grupos['diferencial']),
            'definitivo'  => implode('; ', $grupos['definitivo']),
        ];
    }

    private function resolverActitud(array $examen): array
    {
        $conocidas = ['alerta', 'letargico', 'letárgico', 'estuporoso', 'comatoso'];
        $actitudRaw = trim((string) ($examen['actitud'] ?? ''));
        $actitudNorm = mb_strtolower($actitudRaw, 'UTF-8');
        $otroExplicito = trim((string) ($examen['actitud_otro'] ?? $examen['otro_actitud'] ?? ''));

        $esAlerta     = in_array($actitudNorm, ['alerta'], true);
        $esLetargico  = in_array($actitudNorm, ['letargico', 'letárgico'], true);
        $esEstuporoso = in_array($actitudNorm, ['estuporoso'], true);
        $esComatoso   = in_array($actitudNorm, ['comatoso'], true);

        $esOtra = false;
        $textoOtro = '';

        if ($otroExplicito !== '') {
            $esOtra = true;
            $textoOtro = $otroExplicito;
        } elseif ($actitudRaw !== '' && !in_array($actitudNorm, $conocidas, true)) {
            $esOtra = true;
            $textoOtro = $actitudRaw;
        }

        return [
            'alerta'     => $esAlerta,
            'letargico'  => $esLetargico,
            'estuporoso' => $esEstuporoso,
            'comatoso'   => $esComatoso,
            'otra'       => $esOtra,
            'otro_texto' => $textoOtro,
        ];
    }

    private function renderRecetasTexto(array $recetas): string
    {
        if (empty($recetas)) {
            return '';
        }

        $lineas = [];
        foreach ($recetas as $receta) {
            if (!is_array($receta)) {
                continue;
            }
            $med = trim((string) ($receta['nombre_medicamento'] ?? $receta['medicamento'] ?? ''));
            $dosis = trim((string) ($receta['dosis'] ?? ''));
            $freq = trim((string) ($receta['frecuencia'] ?? ''));
            $dur = trim((string) ($receta['duracion'] ?? ''));

            $extras = array_filter([$dosis, $freq, $dur], static fn (string $v): bool => $v !== '');
            $linea = $med;
            if (!empty($extras)) {
                $linea .= ($linea !== '' ? ' - ' : '') . implode(' / ', $extras);
            }
            if ($linea !== '') {
                $lineas[] = '• ' . $this->esc($linea);
            }
        }

        return implode('<br>', $lineas);
    }

    public function generarPDF(
        string $titulo,
        array $consulta,
        ?array $anamnesis,
        ?array $examen,
        ?array $resultados,
        ?array $diagnostico,
        ?array $recipe
    ): void {
        $fecha = date('d/m/Y H:i');

        $consulta   = $consulta ?? [];
        $anamnesis  = $anamnesis ?? [];
        $examen     = $examen ?? [];
        $resultados = $resultados ?? [];
        $diagnostico = $diagnostico ?? [];
        $recipe     = $recipe ?? [];

        $consulta['numero_historia'] ??= $consulta['id_consulta'] ?? '';
        $consulta['tipo_visita'] = match ((int) ($consulta['tipo_ingreso'] ?? 0)) {
            1 => 'primera',
            2 => 'sucesivo',
            default => (string) ($consulta['tipo_visita'] ?? ''),
        };
        $consulta['remitido_texto'] = isset($consulta['remitido']) && is_numeric((string) $consulta['remitido'])
            ? ((int) $consulta['remitido'] === 1 ? 'Sí' : 'No')
            : (string) ($consulta['remitido'] ?? '');

        $anamnesis['desparasitacion_interna'] = $this->combinar(
            $anamnesis['ult_desp_interna'] ?? null,
            $anamnesis['ult_desp_int_producto'] ?? null
        );
        $anamnesis['desparasitacion_externa'] = $this->combinar(
            $anamnesis['ult_desp_externa'] ?? null,
            $anamnesis['ult_desp_ext_producto'] ?? null
        );
        $anamnesis['frecuencia_alimentacion'] = $anamnesis['frec_alimentacion'] ?? $anamnesis['frecuencia_alimentacion'] ?? '';
        $anamnesis['contacto_animales'] = $anamnesis['contacto_animal'] ?? $anamnesis['contacto_animales'] ?? '';
        $anamnesis['ultimo_celo_monta'] = $anamnesis['fch_ultimo_celo'] ?? $anamnesis['ultimo_celo_monta'] ?? '';
        $anamnesis['higiene'] = $this->combinar(
            $anamnesis['prod_higiene'] ?? null,
            $anamnesis['frec_higiene'] ?? null
        );
        $anamnesis['ambiente'] = $this->combinar(
            $anamnesis['int_ambiente'] ?? null,
            $anamnesis['ext_ambiente'] ?? null
        );
        $anamnesis['presento_ectoparasitos'] = $this->textoSiNo($anamnesis['ant_ectoparasitos'] ?? null);
        $anamnesis['ectoparasitos_actuales'] = $this->textoSiNo($anamnesis['act_ectoparasitos'] ?? null);

        $examen['temperatura'] = $examen['temp_celsius'] ?? $examen['temperatura'] ?? '';
        $examen['fc'] = $examen['fc_lpm'] ?? $examen['fc'] ?? '';
        $examen['fr'] = $examen['fr_rpm'] ?? $examen['fr'] ?? '';
        $examen['pulso'] = $examen['pulso_ppm'] ?? $examen['pulso'] ?? '';
        $examen['ttc'] = $examen['tlc_seg'] ?? $examen['ttc'] ?? '';
        $examen['tpc'] = $examen['tpc_seg'] ?? $examen['tpc'] ?? '';
        $examen['deshidratacion'] = $examen['prcnt_deshidratacion'] ?? $examen['deshidratacion'] ?? '';
        $examen['mucosas'] = $examen['mucosas_visibles'] ?? $examen['mucosas'] ?? '';
        $examen['ganglios'] = $examen['gangliios_palpables'] ?? $examen['ganglios_palpables'] ?? $examen['ganglios'] ?? '';

        $actitudData = $this->resolverActitud($examen);
        $dxClasificados = $this->clasificarDiagnosticos($diagnostico);
        $recetasTextoHtml = $this->renderRecetasTexto($recipe);
        $camposUroanalisis = [
            'uro_sangre', 'uro_urob', 'uro_bli', 'uro_bil', 'uro_prot', 'uro_nitritos',
            'uro_cetona', 'uro_glucosa', 'uro_ph', 'uro_leu', 'uro_densidad',
            'uro_microorganismos', 'uro_celulas', 'uro_cilindros', 'uro_cristales',
        ];
        $uroanalisisActivo = false;
        foreach ($camposUroanalisis as $campo) {
            if ($this->tieneValor($resultados[$campo] ?? null)) {
                $uroanalisisActivo = true;
                break;
            }
        }

        $rutaLogo = dirname(__DIR__, 2) . '/public/img/FarmovetLogo.jpg';
        $logoSrc = '';
        if (is_file($rutaLogo)) {
            $mime = mime_content_type($rutaLogo) ?: 'image/jpeg';
            $logoSrc = 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($rutaLogo));
        }

        $tituloEsc = $this->esc($titulo !== '' ? $titulo : 'HISTORIA CLÍNICA');
        $fechaEsc  = $this->esc($consulta['fecha'] ?? $fecha);

        $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page {
        margin: 12mm 12mm 12mm 12mm;
    }
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 9px;
        color: #1a1a1a;
        margin: 0;
        padding: 0;
        line-height: 1.25;
    }
    .page-break {
        page-break-after: always;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td, th {
        vertical-align: top;
        padding: 2px 4px;
    }
    .header-table {
        margin-bottom: 5px;
        border-bottom: 1.5px solid #4a148c;
        padding-bottom: 4px;
    }
    .logo-img {
        max-height: 52px;
        max-width: 160px;
    }
    .doc-title {
        font-size: 14.5px;
        font-weight: bold;
        color: #222;
        text-align: center;
        letter-spacing: 0.5px;
        padding-top: 6px;
    }
    .doc-title span {
        display: inline-block;
        border-bottom: 2px solid #222;
        padding-bottom: 2px;
    }
    .meta-table td {
        padding: 3px 4px;
        font-size: 8.2px;
    }
    .section-title {
        font-weight: bold;
        font-size: 9.5px;
        color: #4a148c;
        background-color: #f3eef8;
        padding: 2.5px 6px;
        margin-top: 6px;
        margin-bottom: 3px;
        border-left: 3px solid #2e7d32;
        page-break-after: avoid;
    }
    .paciente-table {
        table-layout: fixed;
    }
    .paciente-table td {
        padding-top: 6px;
        padding-bottom: 6px;
    }
    .anamnesis-table {
        table-layout: fixed;
    }
    .anamnesis-table td {
        padding-top: 6.5px;
        padding-bottom: 6.5px;
    }
    .examen-table {
        table-layout: fixed;
    }
    .examen-table td {
        padding-top: 6.5px;
        padding-bottom: 6.5px;
    }
    .back-section-table {
        table-layout: fixed;
    }
    .back-section-table td {
        padding-top: 6px;
        padding-bottom: 6px;
    }
    .field-label {
        font-weight: bold;
        color: #222;
    }
    .field-box {
        border-bottom: 1px dotted #999;
        padding-bottom: 2px;
        min-height: 14px;
    }
    .multiline-box {
        border-bottom: 1px dotted #999;
        background-color: transparent;
        padding: 2px 4px 4px 4px;
        min-height: 22px;
        margin-bottom: 3px;
    }
    td, .field-box, .multiline-box {
        word-wrap: break-word;
    }
    .chk {
        display: inline-block;
        width: 10.5px;
        height: 10.5px;
        border: 1px solid #333;
        text-align: center;
        line-height: 10.5px;
        font-size: 8px;
        font-weight: bold;
        margin: 0 2px;
    }
    .uro-table {
        margin-top: 3px;
    }
    .uro-table td {
        padding: 2.2px 4px;
        font-size: 8.2px;
        border-bottom: 1px dotted #ddd;
    }
    .footer-note {
        margin-top: 8px;
        font-size: 8px;
        color: #666;
        text-align: right;
    }
</style>
</head>
<body>

<!-- ==================== PÁGINA 1: PARTE DELANTERA ==================== -->
<div>
    <table class="header-table">
        <tr>
            <td style="width: 28%;">
                {$this->renderLogoTag($logoSrc)}
            </td>
            <td style="width: 44%;" class="doc-title">
                <span>{$tituloEsc}</span>
            </td>
            <td style="width: 28%;">
                <table class="meta-table">
                    <tr>
                        <td><span class="field-label">Fecha:</span> {$fechaEsc}</td>
                    </tr>
                    <tr>
                        <td><span class="field-label">Historia #:</span> {$this->esc($consulta['numero_historia'] ?? '')}</td>
                    </tr>
                    <tr>
                        <td>
                            <span class="chk">{$this->check(($consulta['tipo_visita'] ?? '') === 'primera')}</span> 1era Vez
                            &nbsp;
                            <span class="chk">{$this->check(($consulta['tipo_visita'] ?? '') === 'sucesivo')}</span> Sucesivo
                        </td>
                    </tr>
                    <tr>
                        <td><span class="field-label">Remitido:</span> {$this->esc($consulta['remitido_texto'] ?? '')}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Datos del Paciente y Propietario (distribución original) -->
    <table class="paciente-table">
        <tr>
            <td style="width: 30%;" class="field-box"><span class="field-label">Nombre del paciente:</span> {$this->esc($consulta['nombre_paciente'] ?? '')}</td>
            <td style="width: 18%;" class="field-box"><span class="field-label">Especie:</span> {$this->esc($consulta['especie'] ?? '')}</td>
            <td style="width: 18%;" class="field-box"><span class="field-label">Raza:</span> {$this->esc($consulta['raza'] ?? '')}</td>
            <td style="width: 16%;" class="field-box"><span class="field-label">Sexo:</span> {$this->esc($consulta['sexo'] ?? '')}</td>
            <td style="width: 18%;" class="field-box"><span class="field-label">F.Nac:</span> {$this->esc($consulta['fecha_nacimiento'] ?? '')}</td>
        </tr>
        <tr>
            <td style="width: 30%;" class="field-box"><span class="field-label">Edad:</span> {$this->esc($consulta['edad'] ?? '')}</td>
            <td style="width: 18%;" class="field-box"><span class="field-label">Pelaje:</span> {$this->esc($consulta['pelaje'] ?? '')}</td>
            <td style="width: 18%;" class="field-box"><span class="field-label">Chip:</span> {$this->esc($consulta['chip'] ?? '')}</td>
            <td colspan="2" style="width: 34%;" class="field-box"><span class="field-label">Procedencia:</span> {$this->esc($consulta['procedencia'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="3" style="width: 66%;" class="field-box"><span class="field-label">Nombre del propietario:</span> {$this->esc($consulta['nombre_propietario'] ?? '')}</td>
            <td colspan="2" style="width: 34%;" class="field-box"><span class="field-label">C.I:</span> {$this->esc($consulta['ci_propietario'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" style="width: 48%;" class="field-box"><span class="field-label">Correo:</span> {$this->esc($consulta['correo'] ?? '')}</td>
            <td style="width: 18%;" class="field-box"><span class="field-label">Teléfono:</span> {$this->esc($consulta['telefono'] ?? '')}</td>
            <td colspan="2" style="width: 34%;" class="field-box"><span class="field-label">Dirección:</span> {$this->esc($consulta['direccion'] ?? '')}</td>
        </tr>
    </table>

    <!-- Anamnesis -->
    <div class="section-title">Anamnesis</div>
    <table class="anamnesis-table">
        <tr>
            <td colspan="2" class="multiline-box">
                <span class="field-label">Motivo de consulta:</span> {$this->esc($anamnesis['motivo_consulta'] ?? '')}
            </td>
        </tr>
        <tr>
            <td style="width: 50%;" class="field-box"><span class="field-label">Inicio de la Enfermedad:</span> {$this->esc($anamnesis['inicio_enfermedad'] ?? '')}</td>
            <td style="width: 50%;" class="field-box"><span class="field-label">Exámenes efectuados:</span> {$this->esc($anamnesis['examenes_efectuados'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" class="field-box"><span class="field-label">Tratamientos Realizados:</span> {$this->esc($anamnesis['tratamientos_realizados'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" class="field-box"><span class="field-label">Última desparasitación interna y producto:</span> {$this->esc($anamnesis['desparasitacion_interna'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" class="field-box"><span class="field-label">Última desparasitación externa y producto:</span> {$this->esc($anamnesis['desparasitacion_externa'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" class="field-box"><span class="field-label">Última vacunación:</span> {$this->esc($anamnesis['ultima_vacunacion'] ?? '')}</td>
        </tr>
        <tr>
            <td style="width: 50%;" class="field-box"><span class="field-label">Tipo de alimentación:</span> {$this->esc($anamnesis['tipo_alimentacion'] ?? '')}</td>
            <td style="width: 50%;" class="field-box"><span class="field-label">Frecuencia de la alimentación:</span> {$this->esc($anamnesis['frecuencia_alimentacion'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">Apetito:</span> {$this->esc($anamnesis['apetito'] ?? '')}</td>
            <td class="field-box"><span class="field-label">Ingesta de agua:</span> {$this->esc($anamnesis['ingesta_agua'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">Vómito:</span> {$this->esc($anamnesis['vomito'] ?? '')}</td>
            <td class="field-box"><span class="field-label">Contacto con otros animales:</span> {$this->esc($anamnesis['contacto_animales'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">Heces:</span> {$this->esc($anamnesis['heces'] ?? '')}</td>
            <td class="field-box"><span class="field-label">Micción:</span> {$this->esc($anamnesis['miccion'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" class="field-box"><span class="field-label">Enfermedades sufridas:</span> {$this->esc($anamnesis['enfermedades_sufridas'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">Alergias:</span> {$this->esc($anamnesis['alergias'] ?? '')}</td>
            <td class="field-box"><span class="field-label">Cirugías previas:</span> {$this->esc($anamnesis['cirugias_previas'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">Higiene (producto y frecuencia):</span> {$this->esc($anamnesis['higiene'] ?? '')}</td>
            <td class="field-box"><span class="field-label">Fecha de último celo/monta:</span> {$this->esc($anamnesis['ultimo_celo_monta'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">Ha presentado ectoparásitos:</span> {$this->esc($anamnesis['presento_ectoparasitos'] ?? '')}</td>
            <td class="field-box"><span class="field-label">Ambiente interior y exterior:</span> {$this->esc($anamnesis['ambiente'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" class="field-box"><span class="field-label">Actualmente presenta ectoparásitos:</span> {$this->esc($anamnesis['ectoparasitos_actuales'] ?? '')}</td>
        </tr>
    </table>

    <!-- Examen clínico -->
    <div class="section-title">Examen clínico</div>
    <table class="examen-table">
        <tr>
            <td style="width: 25%;" class="field-box"><span class="field-label">Peso:</span> {$this->esc($examen['peso'] ?? '')}</td>
            <td style="width: 25%;" class="field-box"><span class="field-label">C.C (1-9):</span> {$this->esc($examen['cc'] ?? '')}</td>
            <td style="width: 25%;" class="field-box"><span class="field-label">Temp °C:</span> {$this->esc($examen['temperatura'] ?? '')}</td>
            <td style="width: 25%;" class="field-box"><span class="field-label">Pulso yugular:</span> {$this->esc($examen['pulso_yugular'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">F.C (lpm):</span> {$this->esc($examen['fc'] ?? '')}</td>
            <td class="field-box"><span class="field-label">F.R (rpm):</span> {$this->esc($examen['fr'] ?? '')}</td>
            <td class="field-box"><span class="field-label">Pulso (ppm):</span> {$this->esc($examen['pulso'] ?? '')}</td>
            <td class="field-box"><span class="field-label">PAS:</span> {$this->esc($examen['pas'] ?? '')}</td>
        </tr>
        <tr>
            <td class="field-box"><span class="field-label">PAD:</span> {$this->esc($examen['pad'] ?? '')}</td>
            <td class="field-box"><span class="field-label">T.L.C (seg):</span> {$this->esc($examen['ttc'] ?? '')}</td>
            <td class="field-box"><span class="field-label">T.P.C (seg):</span> {$this->esc($examen['tpc'] ?? '')}</td>
            <td class="field-box"><span class="field-label">% de deshidratación:</span> {$this->esc($examen['deshidratacion'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="2" class="field-box"><span class="field-label">Mucosas visibles:</span> {$this->esc($examen['mucosas'] ?? '')}</td>
            <td colspan="2" class="field-box"><span class="field-label">Ectoparásitos:</span> {$this->esc($examen['ectoparasitos'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="4" class="field-box"><span class="field-label">Ganglios palpables:</span> {$this->esc($examen['ganglios'] ?? '')}</td>
        </tr>
        <tr>
            <td colspan="4" class="field-box">
                <span class="field-label">Actitud y temperamento:</span>
                <span class="chk">{$this->check($actitudData['alerta'])}</span> Alerta
                <span class="chk">{$this->check($actitudData['letargico'])}</span> Letárgico
                <span class="chk">{$this->check($actitudData['estuporoso'])}</span> Estuporoso
                <span class="chk">{$this->check($actitudData['comatoso'])}</span> Comatoso
                &nbsp;|&nbsp;
                <span class="chk">{$this->check($actitudData['otra'])}</span>
                <span class="field-label">Otro:</span> {$this->esc($actitudData['otro_texto'])}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="multiline-box">
                <span class="field-label">Hallazgos al examen clínico:</span> {$this->esc($examen['hallazgos'] ?? '')}
            </td>
        </tr>
    </table>
</div>

<div class="page-break"></div>

<!-- ==================== PÁGINA 2: PARTE TRASERA ==================== -->
<div>
    <!-- Diagnósticos -->
    <div class="section-title">Diagnósticos</div>
    <table class="back-section-table">
        <tr>
            <td style="width: 50%;" class="multiline-box">
                <span class="field-label">Dx tentativo:</span> {$this->esc($dxClasificados['tentativo'])}
            </td>
            <td style="width: 50%;" class="multiline-box">
                <span class="field-label">Dx diferenciales:</span> {$this->esc($dxClasificados['diferencial'])}
            </td>
        </tr>
    </table>

    <!-- Exámenes ordenados -->
    <div class="section-title">Exámenes ordenados</div>
    <div class="multiline-box" style="min-height: 28px;">{$this->esc($resultados['examenes_ordenados'] ?? $anamnesis['examenes_efectuados'] ?? '')}</div>

    <!-- Resultados -->
    <div class="section-title">Resultados</div>
    <table class="lab-results back-section-table">
        <tr>
            <td style="width: 50%;">
                <div class="multiline-box" style="min-height: 44px;">
                    <span class="field-label">Hematología completa</span>
                    <span class="chk">{$this->check($this->tieneValor($resultados['hematologia_completa'] ?? null))}</span><br>
                    {$this->esc($resultados['hematologia_completa'] ?? '')}
                </div>
            </td>
            <td style="width: 50%;">
                <div class="multiline-box" style="min-height: 44px;">
                    <span class="field-label">Coprología</span>
                    <span class="chk">{$this->check($this->tieneValor($resultados['coprologia'] ?? null))}</span><br>
                    {$this->esc($resultados['coprologia'] ?? '')}
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="multiline-box">
                    <span class="field-label">Uroanálisis</span>
                    <span class="chk">{$this->check($uroanalisisActivo)}</span>
                    <table class="uro-table">
                        <tr><td>Sangre:</td><td>{$this->esc($resultados['uro_sangre'] ?? '')}</td></tr>
                        <tr><td>Urob:</td><td>{$this->esc($resultados['uro_urob'] ?? '')}</td></tr>
                        <tr><td>Bil:</td><td>{$this->esc($resultados['uro_bli'] ?? $resultados['uro_bil'] ?? '')}</td></tr>
                        <tr><td>Prot:</td><td>{$this->esc($resultados['uro_prot'] ?? '')}</td></tr>
                        <tr><td>Nitritos:</td><td>{$this->esc($resultados['uro_nitritos'] ?? '')}</td></tr>
                        <tr><td>Cetona:</td><td>{$this->esc($resultados['uro_cetona'] ?? '')}</td></tr>
                        <tr><td>Glucosa:</td><td>{$this->esc($resultados['uro_glucosa'] ?? '')}</td></tr>
                        <tr><td>pH:</td><td>{$this->esc($resultados['uro_ph'] ?? '')}</td></tr>
                        <tr><td>Leu:</td><td>{$this->esc($resultados['uro_leu'] ?? '')}</td></tr>
                        <tr><td>Densidad:</td><td>{$this->esc($resultados['uro_densidad'] ?? '')}</td></tr>
                        <tr><td>Microorganismos:</td><td>{$this->esc($resultados['uro_microorganismos'] ?? '')}</td></tr>
                        <tr><td>Células:</td><td>{$this->esc($resultados['uro_celulas'] ?? '')}</td></tr>
                        <tr><td>Cilindros:</td><td>{$this->esc($resultados['uro_cilindros'] ?? '')}</td></tr>
                        <tr><td>Cristales:</td><td>{$this->esc($resultados['uro_cristales'] ?? '')}</td></tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="multiline-box" style="min-height: 52px;">
                    <span class="field-label">Química sanguínea</span>
                    <span class="chk">{$this->check($this->tieneValor($resultados['quimica_sanguinea'] ?? null))}</span><br>
                    {$this->esc($resultados['quimica_sanguinea'] ?? '')}
                </div>
                <div class="multiline-box" style="min-height: 52px;">
                    <span class="field-label">Piel u otros</span>
                    <span class="chk">{$this->check($this->tieneValor($resultados['piel_otros'] ?? null))}</span><br>
                    {$this->esc($resultados['piel_otros'] ?? '')}
                </div>
                <div class="field-box" style="padding-top: 6px; padding-bottom: 6px;"><span class="field-label">Descarte:</span> {$this->esc($resultados['descarte'] ?? '')}</div>
                <div class="field-box" style="padding-top: 6px; padding-bottom: 6px;"><span class="field-label">Obs:</span> {$this->esc($resultados['observaciones'] ?? '')}</div>
                <div class="field-box" style="padding-top: 6px; padding-bottom: 6px;"><span class="field-label">SNAP:</span> {$this->esc($resultados['snap'] ?? '')}</div>
            </td>
        </tr>
    </table>

    <!-- Diagnóstico definitivo y Pronóstico -->
    <table class="back-section-table">
        <tr>
            <td style="width: 55%;" class="multiline-box" style="min-height: 36px;">
                <span class="field-label">Diagnóstico definitivo:</span> {$this->esc($dxClasificados['definitivo'])}
            </td>
            <td style="width: 45%;" class="multiline-box" style="min-height: 36px;">
                <span class="field-label">Pronóstico:</span> {$this->esc($consulta['pronostico'] ?? '')}
            </td>
        </tr>
    </table>

    <!-- Tratamiento en consulta y Récipe -->
    <table class="back-section-table">
        <tr>
            <td style="width: 50%;" class="multiline-box" style="min-height: 65px;">
                <span class="field-label">Tratamiento en consulta</span> (Peso: {$this->esc($consulta['peso_consulta'] ?? $examen['peso'] ?? '')}):<br>
                {$this->esc($consulta['tratamiento_consulta'] ?? '')}
            </td>
            <td style="width: 50%;" class="multiline-box" style="min-height: 65px;">
                <span class="field-label">Récipe:</span><br>
                {$recetasTextoHtml}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="multiline-box" style="min-height: 36px;">
                <span class="field-label">Comentario clínico y/o seguimiento:</span> {$this->esc($consulta['comentario'] ?? '')}
            </td>
        </tr>
    </table>

    <div class="footer-note">Generado el {$this->esc($fecha)}</div>
</div>

</body>
</html>
HTML;

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        $this->dompdf->stream('consulta.pdf', ['Attachment' => 0]);
    }

    private function renderLogoTag(string $logoSrc): string
    {
        if ($logoSrc === '') {
            return '<strong style="color:#4a148c;font-size:13px;">Farmo<span style="color:#2e7d32;">Vet</span></strong>';
        }
        return '<img src="' . $logoSrc . '" alt="FarmoVet" class="logo-img">';
    }
}