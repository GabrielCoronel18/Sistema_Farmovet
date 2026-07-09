<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;
use PDO;

class Cliente extends ConexionBD {
    

    public function registrar($nombre, $apellido, $cedula, $telefono, $correo, $direccion) {
        $sql = "INSERT INTO cliente (cedula_cliente, nombre, apellido, correo, telefono, direccion, estado) VALUES (:cedula, :nombre, :apellido, :correo, :telefono, :direccion, :estado)";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindValue(':estado', 1);

        return $stmt->execute();
    }

public function filtrarCliente($param, int $pagina, int $limitacion){
            $limite = $pagina * $limitacion;
            $offset = $limite - $limitacion;
            $busqueda = $param . "%";
            $conex = $this->getConexion();
            $sql = "SELECT * FROM cliente
                    WHERE estado = 1 
                            AND (cedula_cliente LIKE :param
                            OR nombre LIKE :param
                            OR apellido LIKE :param
                            OR correo LIKE :param
                            OR telefono LIKE :param
                            OR direccion LIKE :param)
                            LIMIT :limitacion OFFSET :offset";
                
            $query = $conex->prepare($sql);
                     $query->bindParam(":param",$busqueda);
                     $query->bindParam(":limitacion",$limitacion,\PDO::PARAM_INT);
                     $query->bindParam(":offset",$offset,\PDO::PARAM_INT);

            $query->execute();
             
            return $query->fetchAll();

     }

    public function obtenercliente(int $pagina, int $limitacion){
             
            $limite = $pagina * $limitacion;
            $offset = $limite - $limitacion;

            $conex = $this->getConexion();
            $sql = "SELECT * FROM cliente  
                    WHERE estado = 1 LIMIT :limitacion OFFSET :offset ";
            
            $query = $conex->prepare($sql);
                     $query->bindParam(":limitacion",$limitacion,\PDO::PARAM_INT);
                      $query->bindParam(":offset",$offset,\PDO::PARAM_INT);
            $query->execute();
             
            return $query->fetchAll();

     }

    public function validarCliente($cedula){
        $sql = "SELECT * FROM cliente where cedula_cliente = :cedula";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarCliente($cedula) {
        $sql = "UPDATE cliente SET estado = '0' WHERE cedula_cliente = :cedula";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        return $stmt->execute();
    }

    public function modificarCliente($cedula, $nombre, $apellido, $correo, $telefono, $direccion){
        $sql = "UPDATE cliente SET nombre = :nombre, apellido = :apellido, correo = :correo, telefono = :telefono, direccion = :direccion WHERE cedula_cliente = :cedula";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':direccion', $direccion);
        return $stmt->execute();
    }

         public function obtenerClientePorId(int $id){


            $conex = $this->getConexion();
            $sql = "SELECT * FROM cliente WHERE cedula_cliente = :id AND estado = 1";
            
            $query = $conex->prepare($sql);
                     $query->bindParam(":id",$id);
            $query->execute();
             
            return $query->fetch();

     }
}
