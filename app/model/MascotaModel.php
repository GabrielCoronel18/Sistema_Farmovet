<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDOStatement;

class Mascota extends ConexionBD{
     private int $id;
     private string $nombre;
     private int $edad;
     private string $sexo;
     private bool $chip;
     private string $procedencia;
     private string $fecha_nacimiento;
     private int $raza;
     private string $pelaje;
     private string $cliente;

     
     
     public function agregarMascota(string $nombre, int $edad,string $sexo,bool $chip,string $procedencia,string $fecha_nacimiento,int $raza,string $pelaje, string $cliente ){
          $this->nombre = $nombre;
          $this->edad = $edad;
          $this->sexo = $sexo;
          $this->chip = $chip;
          $this->procedencia = $procedencia;
          $this->fecha_nacimiento = $fecha_nacimiento;
          $this->raza = $raza;
          $this->pelaje = $pelaje;
          $this->cliente = $cliente;
           
          $conex = $this->getConexion();
          $sql = "INSERT INTO mascota (nombre,edad,sexo,chip,procedencia,fch_nacimiento,id_raza,pelaje,cedula_cliente,estado)
                  VALUES(:nombre,:edad,:sexo,:chip,:procedencia,:fecha_nacimiento,:raza,:pelaje,:cliente, 1)";
                       
                    $query = $conex->prepare($sql);
                        $query->bindParam(":nombre",$this->nombre);
                        $query->bindParam(":edad",$this->edad);
                        $query->bindParam(":sexo",$this->sexo);
                        $query->bindParam(":chip",$this->chip);
                        $query->bindParam(":procedencia",$this->procedencia);
                        $query->bindParam(":fecha_nacimiento",$this->fecha_nacimiento);
                        $query->bindParam(":raza",$this->raza);
                        $query->bindParam(":pelaje",$this->pelaje);
                        $query->bindParam(":cliente",$this->cliente);
                    
                    return $query->execute();   
                        
           
     }


     public function obtenerMascotas(int $pagina, int $limitacion){
             
            $limite = $pagina * $limitacion;
            $offset = $limite - $limitacion;

            $conex = $this->getConexion();
            $sql = "SELECT mascota.*, cliente.nombre AS nombre_cliente, raza.nombre_raza FROM mascota  
                    INNER JOIN cliente ON mascota.cedula_cliente = cliente.cedula_cliente
                    INNER JOIN raza ON mascota.id_raza = raza.id_raza   
                    WHERE mascota.estado = 1 LIMIT :limitacion OFFSET :offset ";
            
            $query = $conex->prepare($sql);
                     $query->bindParam(":limitacion",$limitacion,\PDO::PARAM_INT);
                      $query->bindParam(":offset",$offset,\PDO::PARAM_INT);
            $query->execute();
             
            return $query->fetchAll();

     }
     
     public function obtenerMascotaPorId(int $id,){
             
            $this->id = $id;

            $conex = $this->getConexion();
            $sql = "SELECT * FROM mascota WHERE id_mascota = :id AND estado = 1";
            
            $query = $conex->prepare($sql);
                     $query->bindParam(":id",$this->id);
            $query->execute();
             
            return $query->fetch();

     }

     
    public function filtrarMascota($param, int $pagina, int $limitacion){
            $limite = $pagina * $limitacion;
            $offset = $limite - $limitacion;
            $busqueda = $param . "%";
            $conex = $this->getConexion();
            $sql = "SELECT mascota.*, cliente.nombre AS cliente_nombre, raza.nombre_raza FROM mascota 
                    INNER JOIN cliente ON mascota.cedula_cliente = cliente.cedula_cliente
                    INNER JOIN raza ON mascota.id_raza = raza.id_raza  
                    WHERE mascota.estado = 1 
                            AND (id_mascota LIKE :param
                            OR mascota.nombre LIKE :param
                            OR edad LIKE :param
                            OR sexo LIKE :param
                            OR procedencia LIKE :param
                            OR raza.nombre_raza LIKE :param
                            OR pelaje LIKE :param
                            OR mascota.cedula_cliente LIKE :param
                            OR cliente.nombre LIKE :param)
                            LIMIT :limitacion OFFSET :offset";
                
            $query = $conex->prepare($sql);
                     $query->bindParam(":param",$busqueda);
                     $query->bindParam(":limitacion",$limitacion,\PDO::PARAM_INT);
                     $query->bindParam(":offset",$offset,\PDO::PARAM_INT);

            $query->execute();
             
            return $query->fetchAll();

     }


    public function actualizarMascota(int $id, string $nombre, int $edad,string $sexo,bool $chip,string $procedencia,string $fecha_nacimiento,int $raza,string $pelaje, string $cliente ){
          
          
          $this->id = $id;
          $this->nombre = $nombre;
          $this->edad = $edad;
          $this->sexo = $sexo;
          $this->chip = $chip;
          $this->procedencia = $procedencia;
          $this->fecha_nacimiento = $fecha_nacimiento;
          $this->raza = $raza;
          $this->pelaje = $pelaje;
          $this->cliente = $cliente;
            
            $conex = $this->getConexion();
            $sql = "UPDATE mascota 
                    SET nombre = :nombre,
                        edad = :edad,    
                        sexo = :sexo,
                        procedencia = :procedencia,  
                        fch_nacimiento = :fecha_nacimiento,  
                        id_raza = :raza,
                        pelaje = :pelaje,  
                        cedula_cliente = :cliente,      
                    WHERE id = :id";     
            
            $query = $conex->prepare($sql);
                    $query->bindParam(":id", $this->$id);
                    $query->bindParam(":nombre",$this->nombre);
                    $query->bindParam(":edad",$this->edad);
                    $query->bindParam(":sexo",$this->sexo);
                    $query->bindParam(":chip",$this->chip);
                    $query->bindParam(":procedencia",$this->procedencia);
                    $query->bindParam(":fecha_nacimiento",$this->fecha_nacimiento);
                    $query->bindParam(":raza",$this->raza);
                    $query->bindParam(":pelaje",$this->pelaje);
                    $query->bindParam(":cliente",$this->cliente);

            return $query->execute();

     }
    

    public function eliminarMascota(int $id){
               
            $conex = $this->getConexion();
            $sql = "UPDATE mascota SET estado = 0 WHERE id = :id";     
            $query = $conex->prepare($sql);
            $query->bindParam(":id", $id);

            return $query->execute();
     }


}
