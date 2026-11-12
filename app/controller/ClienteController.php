<?php

use Gabriel\SistemaFarmovet\model\ClienteModel;

use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

$cliente = new ClienteModel();

$mensajeAlerta = "";

    if(isset($_POST["agregar"])){

    verificarRol([1, 2]);

    
        $nombre = trim($_POST["nombre"] ?? "");
        $apellido = trim($_POST["apellido"] ?? "");
        $cedula = strtoupper(trim($_POST["cedula_cliente"] ?? ""));
        $telefono = trim($_POST["telefono"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $direccion = trim($_POST["direccion"] ?? "");

    $cliente->clearErrors();
	
	$cliente->validateRequired(['nombre' => $nombre, 'apellido' => $apellido, 'cedula' => $cedula, 'telefono' => $telefono, 'correo' => $correo, 'direccion' => $direccion], ['nombre', 'apellido', 'cedula','telefono','correo','direccion']);
	
	$cliente->validateMinLength($nombre, 'nombre', 3);
	$cliente->validateMaxLength($nombre, 'nombre', 30);
	$cliente->validateNombreCliente($nombre, 'nombre');
    
    $cliente->validateMinLength($apellido, 'apellido', 3);
	$cliente->validateMaxLength($apellido, 'apellido', 30);
    $cliente->validateNombreCliente($apellido, 'apellido');

    $cliente->validateMinLength($cedula, 'cedula', 8);
	$cliente->validateMaxLength($cedula, 'cedula', 12);
    $cliente->validateCedulaCliente($cedula);
    
    $cliente->validateMinLength($telefono, 'telefono', 9);
	$cliente->validateMaxLength($telefono, 'telefono', 11);
    $cliente->validateNumeric($telefono, 'telefono');
    $cliente->validateTelefonoCliente($telefono);

    $cliente->validateMinLength($correo, 'correo', 5);
	$cliente->validateMaxLength($correo, 'correo', 60);
    $cliente->validateCorreoCliente($correo);

    $cliente->validateMinLength($direccion, 'direccion', 10);
	$cliente->validateMaxLength($direccion, 'direccion', 50);
    $cliente->validateDireccionCliente($direccion);
	
	
	$errors = $cliente->getErrors();
	if(!empty($errors)) {
		echo json_encode([
			"status" => "error",
			"errors" => $errors
		]);
		exit;
	}

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

    verificarRol([1,2]);

        $id = trim($_POST["id"] ?? ""); // cedula_cliente passed as id
        $nombre = trim($_POST["nombre"] ?? "");
        $apellido = trim($_POST["apellido"] ?? "");
        $cedula = strtoupper(trim($_POST["cedula_cliente"] ?? $id));
        $telefono = trim($_POST["telefono"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $direccion = trim($_POST["direccion"] ?? "");

        $cliente->clearErrors();
        $cliente->validateRequired(
            [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'cedula' => $cedula,
                'telefono' => $telefono,
                'correo' => $correo,
                'direccion' => $direccion
            ],
            ['nombre', 'apellido', 'cedula', 'telefono', 'correo', 'direccion']
        );

        $cliente->validateMinLength($nombre, 'nombre', 3);
        $cliente->validateMaxLength($nombre, 'nombre', 30);
        $cliente->validateNombreCliente($nombre, 'nombre');
        $cliente->validateMinLength($apellido, 'apellido', 3);
        $cliente->validateMaxLength($apellido, 'apellido', 30);
        $cliente->validateNombreCliente($apellido, 'apellido');
        $cliente->validateMinLength($cedula, 'cedula', 8);
        $cliente->validateMaxLength($cedula, 'cedula', 12);
        $cliente->validateCedulaCliente($cedula);
        $cliente->validateMinLength($telefono, 'telefono', 9);
        $cliente->validateMaxLength($telefono, 'telefono', 11);
        $cliente->validateNumeric($telefono, 'telefono');
        $cliente->validateTelefonoCliente($telefono);
        $cliente->validateMinLength($correo, 'correo', 5);
        $cliente->validateMaxLength($correo, 'correo', 60);
        $cliente->validateCorreoCliente($correo);
        $cliente->validateMinLength($direccion, 'direccion', 10);
        $cliente->validateMaxLength($direccion, 'direccion', 50);
        $cliente->validateDireccionCliente($direccion);

        $errors = $cliente->getErrors();
        if(!empty($errors)) {
            echo json_encode([
                "status" => "error",
                "errors" => $errors
            ]);
            exit;
        }

        if($cliente->modificarCliente($id, $nombre, $apellido, $correo, $telefono, $direccion)){
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode(["status"=>"error"]);
        }
        exit;
    }

    if(isset($_POST["eliminar"]) && isset($_POST["id"])){
        verificarRol([1,2]);

        $id = trim($_POST["id"]);
        
        if($cliente->eliminarCliente($id)){
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode(["status"=>"error"]);
        }
        exit;
    }
    
    // Funciona como el buscador
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
        $limitacion = $_POST["limite"] ?? 500;
        
        
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
    include_once "app/view/ClienteView.php"
?>