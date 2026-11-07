<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\PlanSanitarioModel;
use Gabriel\SistemaFarmovet\helpers\traits\Validation;
use Gabriel\SistemaFarmovet\helpers\traits\PlanSanitarioValidation;

require_once __DIR__ . '/../model/PlanSanitarioModel.php';

class PlanSanitarioController {
    use Validation;
    use PlanSanitarioValidation;

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
                $idMascota = $_POST['id_mascota'] ?? '';
                $idMedicamento = $_POST['id_medicamento'] ?? '';
                $fechaAplicacion = trim($_POST['fecha_aplicacion'] ?? '');
                $proximoRefuerzo = trim($_POST['proximo_refuerzo'] ?? '');

                $this->clearErrors();
                $this->validateIdPlanSanitario($idMascota, 'id_mascota');
                $this->validateIdPlanSanitario($idMedicamento, 'id_medicamento');
                $this->validateFechaPlanSanitario($fechaAplicacion, 'fecha_aplicacion');
                $this->validateFechaPlanSanitario($proximoRefuerzo, 'proximo_refuerzo');
                $this->validateRangoFechasPlanSanitario($fechaAplicacion, $proximoRefuerzo);

                if (!empty($this->getErrors())) {
                    echo json_encode(["status" => "error", "errors" => $this->getErrors()]);
                    exit;
                }
                $res = $this->modelo->registrarPlan($_POST);
                echo json_encode([
                    "status" => $res ? "success" : "error",
                    "mensaje" => $res ? "" : "No se pudo guardar el plan sanitario."
                ]);
                exit;
            }

            if (isset($_POST['action_form']) && $_POST['action_form'] === 'actualizar') {
                $idPlan = $_POST['id_plan'] ?? '';
                $idMascota = $_POST['id_mascota'] ?? '';
                $idMedicamento = $_POST['id_medicamento'] ?? '';
                $fechaAplicacion = trim($_POST['fecha_aplicacion'] ?? '');
                $proximoRefuerzo = trim($_POST['proximo_refuerzo'] ?? '');

                $this->clearErrors();
                $this->validateIdPlanSanitario($idPlan, 'id_plan');
                $this->validateIdPlanSanitario($idMascota, 'id_mascota');
                $this->validateIdPlanSanitario($idMedicamento, 'id_medicamento');
                $this->validateFechaPlanSanitario($fechaAplicacion, 'fecha_aplicacion');
                $this->validateFechaPlanSanitario($proximoRefuerzo, 'proximo_refuerzo');
                $this->validateRangoFechasPlanSanitario($fechaAplicacion, $proximoRefuerzo);

                if (!empty($this->getErrors())) {
                    echo json_encode(["status" => "error", "errors" => $this->getErrors()]);
                    exit;
                }
                $res = $this->modelo->actualizarPlan($_POST);
                echo json_encode([
                    "status" => $res ? "success" : "error",
                    "mensaje" => $res ? "" : "No se pudo actualizar el plan sanitario."
                ]);
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
