<?php
use Gabriel\SistemaFarmovet\model\Cliente;
$cliente = new Cliente();

$mensajeAlerta = "";

if (isset($_POST['enviar'])) {
    if (count($cliente->validarCliente($_POST['cedula'])) == 0) {
        $cliente->registrar($_POST['nombre'],$_POST['apellido'],$_POST['cedula'],$_POST['telefono'],$_POST['correo'],$_POST['direccion']);
        $mensajeAlerta = "Swal.fire({icon: 'success', title: '¡Éxito!', text: 'Cliente registrado exitosamente', showConfirmButton: false, timer: 2000});";
    }else {
        $mensajeAlerta = "Swal.fire({icon: 'error', title: 'Oops...', text: 'La cédula ya se encuentra registrada'});";
    }
}

if (isset($_POST['modificar'])) {
    $cliente->modificarCliente($_POST['cedula'],$_POST['nombre'],$_POST['apellido'],$_POST['correo'],$_POST['telefono'],$_POST['direccion']);
    $mensajeAlerta = "Swal.fire({icon: 'success', title: '¡Éxito!', text: 'Cliente modificado exitosamente', showConfirmButton: false, timer: 2000});";
}

if (isset($_POST['Eliminar'])) {
    $cliente->eliminarCliente($_POST['valorEliminar']);
    $mensajeAlerta = "Swal.fire({icon: 'success', title: 'Eliminado', text: 'Cliente eliminado exitosamente', showConfirmButton: false, timer: 2000});";
}


$datos = $cliente->listar();
require_once "app/view/ClienteView.php"
?>