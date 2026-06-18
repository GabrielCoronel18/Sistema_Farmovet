<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\connect\ConnectDB;
use PDO;

class Cliente extends ConnectDB {
    

    public function registrar($nombre, $apellido, $cedula, $telefono, $correo, $direccion) {
        $sql = "INSERT INTO cliente (cedula_cliente, nombre, apellido, correo, telefono, direccion, estado) VALUES (:cedula, :nombre, :apellido, :correo, :telefono, :direccion, :estado)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindValue(':estado', 1);

        return $stmt->execute();
    }

    public function listar() {
        $sql = "SELECT * FROM cliente where estado = 1";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validarCliente($cedula){
        $sql = "SELECT * FROM cliente where cedula_cliente = :cedula";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarCliente($cedula) {

    $sql= "UPDATE cliente SET estado ='0' WHERE cedula_cliente = :cedula";
    $stmt = $this ->getConnection()->prepare($sql);
     $stmt->bindParam(':cedula', $cedula);
     $stmt->execute();
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modificarCliente($cedula, $nombre, $apellido, $correo, $telefono, $direccion){
        $sql = "UPDATE cliente SET nombre = :nombre, apellido = :apellido, correo = :correo, telefono = :telefono, direccion = :direccion WHERE cedula_cliente = :cedula";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':direccion', $direccion);
        return $stmt->execute();
    }
}
