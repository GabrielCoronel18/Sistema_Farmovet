<?php
namespace Gabriel\SistemaFarmovet\services;

use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteService {
    private Dompdf $dompdf;

    public function __construct() {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        
        $this->dompdf = new Dompdf($options);
    }

    public function generarPDF(string $titulo, array $datos): void {
        $fecha = date('d/m/Y H:i');
        
        // Renderizado del HTML estructurado
        $html = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Helvetica, Arial, sans-serif; color: #1e293b; margin: 30px; }
                header { border-bottom: 2px solid #4f46e5; padding-bottom: 12px; margin-bottom: 20px; }
                h1 { color: #4f46e5; font-size: 20px; margin: 0; }
                .fecha { font-size: 11px; color: #64748b; margin-top: 4px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
                th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
                th { background-color: #f1f5f9; color: #0f172a; font-weight: bold; }
                tr:nth-child(even) { background-color: #f8fafc; }
                .badge { font-weight: bold; padding: 3px 6px; border-radius: 4px; }
            </style>
        </head>
        <body>
            <header>
                <h1>{$titulo}</h1>
                <div class='fecha'>Reporte generado el: {$fecha}</div>
            </header>
            <main>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Proyecto</th>
                            <th>Estado</th>
                            <th>Progreso</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($datos as $item) {
            $html .= "<tr>
                <td>{$item['id']}</td>
                <td>" . htmlspecialchars($item['nombre']) . "</td>
                <td>" . htmlspecialchars($item['estado']) . "</td>
                <td>{$item['progreso']}%</td>
            </tr>";
        }

        $html .= "  </tbody>
                </table>
            </main>
        </body>
        </html>";

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        
        // Envía el PDF directamente al navegador (stream)
        $this->dompdf->stream('reporte_ejecutivo.pdf', ['Attachment' => 0]);
    }
}