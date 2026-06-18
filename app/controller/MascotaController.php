<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\MascotaModel;
use Gabriel\SistemaFarmovet\model\Alrg_MascotaModel;
use Gabriel\SistemaFarmovet\model\Cirgs_PreviasModel;
use Gabriel\SistemaFarmovet\model\Enf_SufridasModel;

$mascotaModel = new MascotaModel();
$AlergiaMascotaModel = new Alrg_MascotaModel();
$CirgsPreviasModel = new Cirgs_PreviasModel();
$EnfSufridasModel = new Enf_SufridasModel();

    if(isset( $_POST["obtener"])){
        $pagina = $_POST["pagina"] ?? 1;
        $limitacion = $_POST["limite"] ?? 5;
        
        
        if(isset($_POST["parametro"])){
          
        $param = $_POST["parametro"];
          $resultados = $mascotaModel->filtrarMascota($param,$pagina,$limitacion);
        }
        else{
          $resultados = $mascotaModel->obtenerMascotas($pagina,$limitacion);
        }

        if($resultados){
           echo json_encode(["status"=>"success","resultados" => $resultados]);
        }
        else{
           echo json_encode(["status"=>"error","resultados" => []]);
        }
      exit;
    }
  
    if(isset($_POST["agregar"])){
        $nombre = $_POST["nombre"] ?? "";
        $edad = $_POST["edad"]  ?? 0;
        $fch_nacimiento = $_POST["fch_nacimiento"] ?? "";
        $sexo = $_POST["sexo"] ?? "";
        $chip = $_POST["chip"] ?? "";
        $id_raza = $_POST["id_raza"] ?? 0;
        $pelaje = $_POST["pelaje"] ?? "";
        $cedula_cliente = $_POST["cedula_cliente"] ?? "";
        $procedencia = $_POST["procedencia"] ?? "";


            if($mascotaModel->agregarMascota($nombre,$edad,$sexo,$chip,$procedencia,$fch_nacimiento,$id_raza,$pelaje,$cedula_cliente)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }

    if(isset($_POST["obtenerMascota"]) && isset($_POST["id"])){
        
        $id = $_POST["id"];

        $resultado = $mascotaModel->obtenerMascotaPorId($id);


            if($resultado){
            echo json_encode(["status"=>"success","resultado" => $resultado]);
            }
            else{
            echo json_encode(["status"=>"error","resultado" => []]);
            }

      exit;
    }

     if(isset($_POST["actualizar"])){
        
     
        $id = $_POST["id"];
        $nombre = $_POST["nombre"] ?? "";
        $edad = $_POST["edad"]  ?? 0;
        $fch_nacimiento = $_POST["fch_nacimiento"] ?? "";
        $sexo = $_POST["sexo"] ?? "";
        $chip = $_POST["chip"] ?? "";
        $id_raza = $_POST["id_raza"] ?? 0;
        $pelaje = $_POST["pelaje"] ?? "";
        $cedula_cliente = $_POST["cedula_cliente"] ?? "";
        $procedencia = $_POST["procedencia"] ?? "";

        if($mascotaModel->actualizarMascota($id,$nombre,$edad,$sexo,$chip,$procedencia,$fch_nacimiento,$id_raza,$pelaje,$cedula_cliente)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
     }


      if(isset($_POST["eliminar"]) && isset($_POST["id"])){
        
        $id = $_POST["id"];

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
        
        $id = $_POST["id"];

        $alergias = $AlergiaMascotaModel->obtenerAlergiasAsociadas($id);
        $cirugias = $CirgsPreviasModel->obtenerCirugiasPrevias($id);
        $enfermedades = $EnfSufridasModel->obtenerEnfermedadesSufridas($id);

            if($alergias !== false && $cirugias !== false && $enfermedades !== false){

            echo json_encode(["status"=>"success","alergias" => $alergias, "cirugias" => $cirugias, "enfermedades" => $enfermedades]);
            }
            else{
            echo json_encode(["status"=>"error","alergias" => [], "cirugias" => [], "enfermedades" => []]);
            }

      exit;
    }
    
    if(isset($_POST["agregarAlergia"]) && isset($_POST["id"])){
        $id = $_POST["id"]  ?? 0;
        $alergia = $_POST["alergias"] ?? 0;
        $fecha = $_POST["fecha_deteccion"] ?? "";
        
            if($AlergiaMascotaModel->asociarAlergia($alergia,$id,$fecha)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }

     if(isset($_POST["agregarCirugia"]) && isset($_POST["id"])){
        $id = $_POST["id"]  ?? 0;
        $cirugia = $_POST["cirugia"] ?? "";
        $fecha = $_POST["fecha_cirugia"] ?? "";
        
            if($CirgsPreviasModel->agregarCirugiaPrevia($id,$cirugia,$fecha)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }

    if(isset($_POST["agregarEnfermedad"]) && isset($_POST["id"])){
        $id = $_POST["id"]  ?? 0;
        $enfermedad = $_POST["enfermedad"] ?? 0;
        $fecha = $_POST["fecha_deteccion"] ?? "";
        $estado = $_POST["estado_enfermedad"] ?? "";

            if($EnfSufridasModel->agregarEnfermedadSufrida($id,$enfermedad,$fecha,$estado)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }

     if(isset($_POST["eliminarAlergia"]) && isset($_POST["id"])){
        
        $id = $_POST["id"];

           if($AlergiaMascotaModel->EliminarAlergiasAsociadas($id)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }
     if(isset($_POST["eliminarCirugia"]) && isset($_POST["id"])){
        
        $id = $_POST["id"];

           if($CirgsPreviasModel->EliminarCirugiaPrevia($id)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }

    if(isset($_POST["eliminarEnfermedad"]) && isset($_POST["id"])){
        
        $id = $_POST["id"];

           if($EnfSufridasModel->EliminarEnfermedadSufrida($id)){
            echo json_encode(["status"=>"success"]);
            }
            else{
            echo json_encode(["status"=>"error"]);
            }

      exit;
    }
require_once "app/view/MascotaView.php";
?>