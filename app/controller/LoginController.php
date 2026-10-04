<?php

use Gabriel\SistemaFarmovet\model\UsuarioModel;

if(isset($_POST["login"])){
      
     $correo = $_POST["correo"];
     $contraseña = $_POST["password"];

     $UsuarioModel = new UsuarioModel;
     
     $usuario = $UsuarioModel->autenticarUsuario($correo,$contraseña);
     
     if($usuario){
   session_regenerate_id(true);
     $_SESSION["usuario"] = $usuario;
            
        header("location:index.php?url=Dashboard");
     exit;

     }
     else{
          
        header("location:index.php?url=Login&error=1");
    exit;
     }
}

require_once "app/view/LoginView.php"
?>