<?php
use Gabriel\SistemaFarmovet\model\Cliente;
$cliente = new Cliente();

$mensajeAlerta = "";

    if(isset($_POST["agregar"])){
        $nombre = trim($_POST["nombre"] ?? "");
        $apellido = trim($_POST["apellido"] ?? "");
        $cedula = trim($_POST["cedula_cliente"] ?? "");
        $telefono = trim($_POST["telefono"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $direccion = trim($_POST["direccion"] ?? "");

        if (count($cliente->validarCliente($cedula)) == 0) {
            if($cliente->registrar($nombre, $apellido, $cedula, $telefono, $correo, $direccion)){
                echo json_encode(["status"=>"success"]);
            } else {
                echo json_encode(["status"=>"error"]);
            }
        } else {
            echo json_encode(["status"=>"error", "message" => "Cédula ya registrada"]);
        }
        exit;
    }

    if(isset($_POST["actualizar"])){
        $id = trim($_POST["id"] ?? ""); // cedula_cliente passed as id
        $nombre = trim($_POST["nombre"] ?? "");
        $apellido = trim($_POST["apellido"] ?? "");
        $telefono = trim($_POST["telefono"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $direccion = trim($_POST["direccion"] ?? "");

        if($cliente->modificarCliente($id, $nombre, $apellido, $correo, $telefono, $direccion)){
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode(["status"=>"error"]);
        }
        exit;
    }

    if(isset($_POST["eliminar"]) && isset($_POST["id"])){
        $id = trim($_POST["id"]);
        
        if($cliente->eliminarCliente($id)){
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode(["status"=>"error"]);
        }
        exit;
    }

    if(isset($_POST["obtenerCliente"]) && isset($_POST["id"])){
        
        $id = $_POST["id"];

        $resultado = $cliente->obtenerClientePorId($id);


            if($resultado){
            echo json_encode(["status"=>"success","resultado" => $resultado]);
            }
            else{
            echo json_encode(["status"=>"error","resultado" => []]);
            }

      exit;
    }


    if(isset( $_POST["obtener"])){
        $pagina = $_POST["pagina"] ?? 1;
        $limitacion = $_POST["limite"] ?? 5;
        
        
        if(isset($_POST["parametro"])){
          
        $param = $_POST["parametro"];
          $resultados = $cliente->filtrarCliente($param,$pagina,$limitacion);
        }
        else{
          $resultados = $cliente->obtenercliente($pagina,$limitacion);
        }

        if($resultados){
           echo json_encode(["status"=>"success","resultados" => $resultados]);
        }
        else{
           echo json_encode(["status"=>"error","resultados" => []]);
        }
      exit;
    }
require_once "app/view/ClienteView.php"
?>