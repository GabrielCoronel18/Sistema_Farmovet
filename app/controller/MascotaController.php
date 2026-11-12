<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\helpers\traits\Validation;
use Gabriel\SistemaFarmovet\model\MascotaModel;
use Gabriel\SistemaFarmovet\model\AlergiasMascotaModel;
use Gabriel\SistemaFarmovet\model\CirugiasMascotaModel;
use Gabriel\SistemaFarmovet\model\EnfermedadesPadecidasModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;
use function Gabriel\SistemaFarmovet\helpers\codificarId;
use function Gabriel\SistemaFarmovet\helpers\decodificarId;

require_once __DIR__ . '/../helpers/hash.php';

verificarRol([1, 2, 3]);

$mascotaModel = new MascotaModel();
$AlergiaMascotaModel = new AlergiasMascotaModel();
$CirgsPreviasModel = new CirugiasMascotaModel();
$EnfSufridasModel = new EnfermedadesPadecidasModel();

$alergiasDisponibles = $AlergiaMascotaModel->obtenerAlergiasActivas();
$enfermedadesDisponibles = $EnfSufridasModel->obtenerPatologiasActivas();
$cirugiasDisponibles = $CirgsPreviasModel->obtenerCirugiasDisponibles();



    if(isset( $_POST["obtener"])){
    $pagina = max(1, (int) ($_POST["pagina"] ?? 1));
    $limitacion = (int) ($_POST["limite"] ?? 5);
    $limitacion = in_array($limitacion, [5, 10, 20], true) ? $limitacion : 5;

    $param = trim((string) ($_POST["parametro"] ?? ""));
    $idFiltrado = $param !== "" ? decodificarId($param) : null;
    if ($idFiltrado !== null) {
        $param = (string) $idFiltrado;
    }
    $tieneFiltro = $param !== "";

    $resultados = $tieneFiltro
        ? $mascotaModel->filtrarMascota($param, $pagina, $limitacion)
        : $mascotaModel->obtenerMascotas($pagina, $limitacion);
    $total = $mascotaModel->contarMascota($tieneFiltro ? $param : null);
        

        if($resultados){
           foreach ($resultados as &$mascota) {
               $mascota['id_mascota_hash'] = codificarId((int) $mascota['id_mascota']);
               unset($mascota['id_mascota']);
           }
           unset($mascota);
           echo json_encode(["status"=>"success","resultados" => $resultados,"hayMas" => $pagina * $limitacion < $total]);
        }
        else{
           echo json_encode(["status"=>"error","resultados" => []]);
        }
      exit;
    }
  
    if(isset($_POST["agregar"])){
        
      verificarRol([1,2]);

        $nombre = $_POST["nombre"] ?? "";
        $edad = $_POST["edad"]  ?? 0;
        $fch_nacimiento = $_POST["fch_nacimiento"] ?? "";
        $sexo = $_POST["sexo"] ?? "";
        $chip = $_POST["chip"] ?? "";
        $id_raza = $_POST["id_raza"] ?? 0;
        $pelaje = $_POST["pelaje"] ?? "";
        $cedula_cliente = $_POST["cedula_cliente"] ?? "";
        $procedencia = $_POST["procedencia"] ?? "";
   
        $mascotaModel->clearErrors();
        
        
	    $mascotaModel->validateRequired(['nombre' => $nombre, 'edad' => $edad, 'sexo' => $sexo, 'chip' => $chip, 'procedencia' => $procedencia, 'fch_nacimiento' => $fch_nacimiento, 'raza' => $id_raza, 'pelaje' => $pelaje, 'cedula' => $cedula_cliente], ['nombre', 'edad', 'sexo', 'chip', 'procedencia', 'fch_nacimiento', 'raza', 'pelaje', 'cedula']);
        $mascotaModel->validateDateFormat($fch_nacimiento, 'Y-m-d', 'fch_nacimiento');
        $mascotaModel->validateInteger($edad, 'edad');
        $mascotaModel->validateInArray($sexo, 'sexo', ['Masculino', 'Femenino']);
        $mascotaModel->validateInArray($chip, 'chip', ['Si', 'No']);
        $mascotaModel->validateMinLength($nombre, 'nombre', 2);
        $mascotaModel->validateMaxLength($nombre, 'nombre', 50);
        $mascotaModel->validateNombreMascota($nombre);
        $mascotaModel->validateMinLength($pelaje, 'pelaje', 4);
        $mascotaModel->validateMaxLength($pelaje, 'pelaje', 50);
        $mascotaModel->validatePelajeMascota($pelaje);
        $mascotaModel->validateMinLength($procedencia, 'procedencia', 5);
        $mascotaModel->validateMaxLength($procedencia, 'procedencia', 200);
        $mascotaModel->validateProcedenciaMascota($procedencia);
        
        
		$errors = $mascotaModel->getErrors();
	    
        if(!empty($errors)) {
		echo json_encode([
			"status" => "error",
			"errors" => $errors
		]);
		exit;
	}

       
 $idMascota = $mascotaModel->agregarMascota($nombre,$edad,$sexo,$chip,$procedencia,$fch_nacimiento,$id_raza,$pelaje,$cedula_cliente);
            if($idMascota){
                $fechaAntecedente = date("Y-m-d");
                foreach ((array) ($_POST["alergias"] ?? []) as $alergia) {
                    $AlergiaMascotaModel->asociarAlergia((int) $alergia, (int) $idMascota, $fechaAntecedente);
                }
                foreach ((array) ($_POST["enfermedades"] ?? []) as $enfermedad) {
                    $EnfSufridasModel->agregarEnfermedadPadecida((int) $idMascota, (int) $enfermedad, $fechaAntecedente, "Leve");
                }
                foreach ((array) ($_POST["cirugias"] ?? []) as $cirugia) {
                    if ((int) $cirugia > 0) {
                        $CirgsPreviasModel->agregarCirugiaPrevia((int) $idMascota, (int) $cirugia, $fechaAntecedente);
                    }
                }

            echo json_encode(["status" => "success"]);
        } else {
            
            echo json_encode(["status" => "error", ]);
        }
    
    exit;
    }

    if(isset($_POST["obtenerMascota"]) && isset($_POST["id"])){
         
       verificarRol([1,2]);
        
        $id = decodificarId((string) $_POST["id"]);
        if ($id === null) {
            echo json_encode(["status" => "error", "mensaje" => "Identificador de mascota inválido."]);
            exit;
        }

        $resultado = $mascotaModel->obtenerMascotaPorId($id);


            if($resultado){
            $resultado['id_mascota_hash'] = codificarId((int) $resultado['id_mascota']);
            unset($resultado['id_mascota']);
            echo json_encode(["status"=>"success","resultado" => $resultado]);
            }
            else{
            echo json_encode(["status"=>"error","resultado" => []]);
            }

      exit;
    }

     if(isset($_POST["actualizar"])){
        
       verificarRol([1,2]);
     
        $id = decodificarId((string) $_POST["id"]);
        if ($id === null) {
            echo json_encode(["status" => "error", "mensaje" => "Identificador de mascota inválido."]);
            exit;
        }
        $nombre = $_POST["nombre"] ?? "";
        $edad = $_POST["edad"]  ?? 0;
        $fch_nacimiento = $_POST["fch_nacimiento"] ?? "";
        $sexo = $_POST["sexo"] ?? "";
        $chip = $_POST["chip"] ?? "";
        $id_raza = $_POST["id_raza"] ?? 0;
        $pelaje = $_POST["pelaje"] ?? "";
        $cedula_cliente = $_POST["cedula_cliente"] ?? "";
        $procedencia = $_POST["procedencia"] ?? "";

        $mascotaModel->clearErrors();
        $mascotaModel->validateRequired(
            ['nombre' => $nombre, 'edad' => $edad, 'sexo' => $sexo, 'chip' => $chip,
             'procedencia' => $procedencia, 'fch_nacimiento' => $fch_nacimiento,
             'raza' => $id_raza, 'pelaje' => $pelaje, 'cedula' => $cedula_cliente],
            ['nombre', 'edad', 'sexo', 'chip', 'procedencia', 'fch_nacimiento',
             'raza', 'pelaje', 'cedula']
        );
        $mascotaModel->validateMinLength($nombre, 'nombre', 2);
        $mascotaModel->validateMaxLength($nombre, 'nombre', 50);
        $mascotaModel->validateNombreMascota($nombre);
        $mascotaModel->validateMinLength($pelaje, 'pelaje', 4);
        $mascotaModel->validateMaxLength($pelaje, 'pelaje', 50);
        $mascotaModel->validatePelajeMascota($pelaje);
        $mascotaModel->validateMinLength($procedencia, 'procedencia', 5);
        $mascotaModel->validateMaxLength($procedencia, 'procedencia', 200);
        $mascotaModel->validateProcedenciaMascota($procedencia);
        $mascotaModel->validateDateFormat($fch_nacimiento, 'Y-m-d', 'fch_nacimiento');
        $mascotaModel->validateInteger($edad, 'edad');
        $mascotaModel->validateInArray($sexo, 'sexo', ['Masculino', 'Femenino']);
        $mascotaModel->validateInArray($chip, 'chip', ['Si', 'No']);
        $errors = $mascotaModel->getErrors();
        if (!empty($errors)) {
            echo json_encode(["status" => "error", "errors" => $errors]);
            exit;
        }
 

        if($mascotaModel->actualizarMascota($id,$nombre,$edad,$sexo,$chip,$procedencia,$fch_nacimiento,$id_raza,$pelaje,$cedula_cliente)){
            $id = (int) $id;
            $fechaAntecedente = date("Y-m-d");
            $AlergiaMascotaModel->eliminarAlergiasDeMascota($id);
            $EnfSufridasModel->eliminarEnfermedadesDeMascota($id);
            $CirgsPreviasModel->eliminarCirugiasDeMascota($id);
            foreach ((array) ($_POST["alergias"] ?? []) as $alergia) {
                $AlergiaMascotaModel->asociarAlergia((int) $alergia, $id, $fechaAntecedente);
            }
            foreach ((array) ($_POST["enfermedades"] ?? []) as $enfermedad) {
                $EnfSufridasModel->agregarEnfermedadPadecida($id, (int) $enfermedad, $fechaAntecedente, "Leve");
            }
            foreach ((array) ($_POST["cirugias"] ?? []) as $cirugia) {
                if ((int) $cirugia > 0) {
                    $CirgsPreviasModel->agregarCirugiaPrevia($id, (int) $cirugia, $fechaAntecedente);
                }
            }
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
     }


      if(isset($_POST["eliminar"]) && isset($_POST["id"])){

       verificarRol([1,2]);
        
        $id = decodificarId((string) $_POST["id"]);
        if ($id === null) {
            echo json_encode(["status" => "error", "mensaje" => "Identificador de mascota inválido."]);
            exit;
        }

           if($mascotaModel->eliminarMascota($id)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }


    /// Antecedentes

    if(isset($_POST["obtenerAntecedentes"]) && isset($_POST["id"])){
        
        $id = decodificarId((string) $_POST["id"]);
        if ($id === null) {
            echo json_encode(["status" => "error", "mensaje" => "Identificador de mascota inválido."]);
            exit;
        }

        $alergias = $AlergiaMascotaModel->obtenerAlergiasAsociadas($id);
        $cirugias = $CirgsPreviasModel->obtenerCirugiasPrevias($id);
        $enfermedades = $EnfSufridasModel->obtenerEnfermedadesPadecidas($id);

            if($alergias !== false && $cirugias !== false && $enfermedades !== false){

            echo json_encode(["status"=>"success","alergias" => $alergias, "cirugias" => $cirugias, "enfermedades" => $enfermedades]);
            }
            else{
            echo json_encode(["status"=>"error","alergias" => [], "cirugias" => [], "enfermedades" => []]);
            }

      exit;
    }
   
        if (isset($_POST["obtenerCatalogos"])) {
        echo json_encode(["status" => "success"] + $mascotaModel->obtenerCatalogos());
        exit;
    }


require_once "app/view/MascotaView.php";
?>