<?php
namespace Gabriel\SistemaFarmovet\config;
use PDO;
use PDOException;

abstract class ConexionBD{
    protected PDO $conex;

    public function __construct(?PDO $conex = null)
    {
        if ($conex !== null) {
            $this->conex = $conex;
        }
    }

    protected function getConexion(): PDO
    {
       if (isset($this->conex)) {
           return $this->conex;
       }

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