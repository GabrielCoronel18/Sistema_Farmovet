<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\PlanSanitarioModel;

require_once __DIR__ . '/../model/PlanSanitarioModel.php';

class PlanSanitarioController {
    
    private $modelo;

    public function __construct() {
        $this->modelo = new PlanSanitarioModel();
    }

    public function procesar() {
        // Manejador de llamadas asíncronas vía Fetch API (JSON)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['obtener']) || isset($_POST['action_form']) || isset($_POST['eliminar']))) {
            header('Content-Type: application/json');
            
            if (isset($_POST['obtener'])) {
                $planes = $this->modelo->consultarPlanes();
                echo json_encode(["resultados" => $planes]);
                exit;
            }

            if (isset($_POST['action_form']) && $_POST['action_form'] === 'registrar') {
                $res = $this->modelo->registrarPlan($_POST);
                echo json_encode(["status" => $res ? "success" : "error"]);
                exit;
            }

            if (isset($_POST['action_form']) && $_POST['action_form'] === 'actualizar') {
                $res = $this->modelo->actualizarPlan($_POST);
                echo json_encode(["status" => $res ? "success" : "error"]);
                exit;
            }

           if (isset($_POST['eliminar'])) {
                $res = $this->modelo->eliminarPlan($_POST['id_plan']);
                echo json_encode(["status" => $res ? "success" : "error"]);
                exit;
            }
        } // <- Aquí cierra el if de los POST

        // LINEA 52: Cambia "AgenteView.php" por "PlanSanitarioView.php"
        // Ajustamos el nombre exacto con el guion bajo tal como lo tienes guardado
        require_once __DIR__ . '/../view/Plan_sanitarioView.php'; 
    }
}

// Disparador del FrontController
$controladorPlan = new PlanSanitarioController();
$controladorPlan->procesar();
