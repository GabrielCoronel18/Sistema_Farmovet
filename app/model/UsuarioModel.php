<?php
namespace Gabriel\SistemaFarmovet\model;
use Gabriel\SistemaFarmovet\config\ConexionBD;

class UsuarioModel extends ConexionBD
{
    private string $cedula;
    private string $nombre;
    private string $apellido;
    private string $telefono;
    private string $correo;
    private string $contraseña;
    private int $rol;
    private int $estado;

    public function agregarUsuario(
        string $cedula,
        string $nombre,
        string $apellido,
        string $telefono,
        string $correo,
        string $contraseña,
        int $rol
    ): bool {
        $conex = $this->getConexion();
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (cedula_usuario, nombre, apellido, telefono, correo, contraseña, id_rol, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        $query = $conex->prepare($sql);
        return $query->execute([$cedula, $nombre, $apellido, $telefono, $correo, $hash, $rol]);
    }

    public function obtenerUsuarios(int $pagina = 1, int $limite = 5): array
    {
        $offset = ($pagina - 1) * $limite;
        $conex = $this->getConexion();
        $sql = "SELECT u.*, r.nombre_rol
                FROM usuario u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.estado = 1
                ORDER BY u.nombre
                LIMIT :limite OFFSET :offset";
        $query = $conex->prepare($sql);
        $query->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }

    public function obtenerUsuarioPorCedula(string $cedula): ?array
    {
        $conex = $this->getConexion();
        $sql = "SELECT u.*, r.nombre_rol
                FROM usuario u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.cedula_usuario = :cedula AND u.estado = 1";
        $query = $conex->prepare($sql);
        $query->bindValue(':cedula', $cedula);
        $query->execute();
        $result = $query->fetch();
        return $result ?: null;
    }

    public function filtrarUsuarios(string $busqueda, int $pagina = 1, int $limite = 5): array
    {
        $offset = ($pagina - 1) * $limite;
        $param = "%" . $busqueda . "%";
        $conex = $this->getConexion();
        $sql = "SELECT u.*, r.nombre_rol
                FROM usuario u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.estado = 1
                  AND (u.cedula_usuario LIKE :param
                       OR u.nombre LIKE :param
                       OR u.apellido LIKE :param
                       OR u.telefono LIKE :param
                       OR u.correo LIKE :param
                       OR r.nombre_rol LIKE :param)
                ORDER BY u.nombre
                LIMIT :limite OFFSET :offset";
        $query = $conex->prepare($sql);
        $query->bindValue(':param', $param);
        $query->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }

    public function actualizarUsuario(
        string $cedulaOriginal,
        string $cedula,
        string $nombre,
        string $apellido,
        string $telefono,
        string $correo,
        ?string $contraseña,
        int $rol
    ): bool {
        $conex = $this->getConexion();
        $sql = "UPDATE usuario SET
                    cedula_usuario = :cedula,
                    nombre = :nombre,
                    apellido = :apellido,
                    telefono = :telefono,
                    correo = :correo,
                    id_rol = :rol";
        if ($contraseña !== null && $contraseña !== '') {
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);
            $sql .= ", contraseña = :contraseña";
        }
        $sql .= " WHERE cedula_usuario = :cedula_original";
        $query = $conex->prepare($sql);
        $query->bindValue(':cedula', $cedula);
        $query->bindValue(':nombre', $nombre);
        $query->bindValue(':apellido', $apellido);
        $query->bindValue(':telefono', $telefono);
        $query->bindValue(':correo', $correo);
        $query->bindValue(':rol', $rol);
        $query->bindValue(':cedula_original', $cedulaOriginal);
        if (isset($hash)) {
            $query->bindValue(':contraseña', $hash);
        }
        return $query->execute();
    }

    public function eliminarUsuario(string $cedula): bool
    {
        $conex = $this->getConexion();
        $sql = "UPDATE usuario SET estado = 0 WHERE cedula_usuario = :cedula";
        $query = $conex->prepare($sql);
        $query->bindValue(':cedula', $cedula);
        return $query->execute();
    }

    public function existeCedula(string $cedula): bool
    {
        $conex = $this->getConexion();
        $sql = "SELECT COUNT(*) FROM usuario WHERE cedula_usuario = :cedula";
        $query = $conex->prepare($sql);
        $query->bindValue(':cedula', $cedula);
        $query->execute();
        return $query->fetchColumn() > 0;
    }
}