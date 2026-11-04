<?php
use Gabriel\SistemaFarmovet\model\ConsultaModel;
use Gabriel\SistemaFarmovet\model\MascotaModel;
use Gabriel\SistemaFarmovet\model\ClienteModel;
use Gabriel\SistemaFarmovet\model\PlanSanitarioModel;

$ConsultaModel = new ConsultaModel;
$MascotaModel = new MascotaModel;
$ClienteModel = new ClienteModel;
$PlanSanitarioModel = new PlanSanitarioModel;

$consultas = $ConsultaModel->contarConsulta();
$mascotas = $MascotaModel->contarMascota();
$clientes = $ClienteModel->contarClientes();
$planes = $PlanSanitarioModel->contarPlanesSanitarios();


include_once "app/view/DashboardView.php";

?>