<?php
namespace Gabriel\SistemaFarmovet\config;
use PDO;
use PDOException;

abstract class ConexionBD{
    protected PDO $conex;

    protected function getConexion(){
       $dsn = "mysql:host=localhost;dbname=farmovet;";
       $username = "root";
       $password = "";
       
       try{
       $this->conex = new PDO($dsn,$username,$password);

       $this->conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
       return $this->conex;
       }
       catch(PDOException $e){
        
         die("Error en la Conexion".$e->getMessage());

       }
    }
}
?>