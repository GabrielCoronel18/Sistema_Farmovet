<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\MascotaModel;
use Gabriel\SistemaFarmovet\model\Alrg_MascotaModel;

$mascotaModel = new MascotaModel();
$AlergiaMascotaModel = new Alrg_MascotaModel();

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


    /// alergia_Mascota

    if(isset($_POST["obtenerAlergiaMascota"]) && isset($_POST["id"])){
        
        $id = $_POST["id"];

        $resultado = $AlergiaMascotaModel->obtenerAlergiasAsociadas($id);


            if($resultado){
            echo json_encode(["status"=>"success","resultado" => $resultado]);
            }
            else{
            echo json_encode(["status"=>"error","resultado" => []]);
            }

      exit;
    }

require_once "app/view/MascotaView.php";
?>