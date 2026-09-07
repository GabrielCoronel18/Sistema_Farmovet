<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;

class CirugiaModel extends ConexionBD {
	private int $id;
	private string $nombre;
	private string $gravedad;

	public function agregarCirugia(string $nombre, string $gravedad) {
		$this->nombre = $nombre;
		$this->gravedad = $gravedad;

		$conex = $this->getConexion();
		$sql = "INSERT INTO cirugia (nombre_cirugia, gravedad, estado)
				VALUES (:nombre, :gravedad, 1)";

		$query = $conex->prepare($sql);
		$query->bindParam(":nombre", $this->nombre);
		$query->bindParam(":gravedad", $this->gravedad);

		return $query->execute();
	}

	public function obtenerCirugias(int $pagina, int $limitacion) {
		$offset = ($pagina * $limitacion) - $limitacion;
		$conex = $this->getConexion();
		$sql = "SELECT * FROM cirugia
				WHERE estado = 1
				ORDER BY nombre_cirugia
				LIMIT :limitacion OFFSET :offset";

		$query = $conex->prepare($sql);
		$query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
		$query->bindParam(":offset", $offset, \PDO::PARAM_INT);
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function obtenerCirugiaPorId(int $id) {
		$this->id = $id;
		$conex = $this->getConexion();
		$query = $conex->prepare("SELECT * FROM cirugia WHERE id_cirugia = :id AND estado = 1");
		$query->bindParam(":id", $this->id, \PDO::PARAM_INT);
		$query->execute();

		return $query->fetch(\PDO::FETCH_ASSOC);
	}

	public function filtrarCirugia(string $param, int $pagina, int $limitacion) {
		$offset = ($pagina * $limitacion) - $limitacion;
		$busqueda = $param . "%";
		$conex = $this->getConexion();
		$sql = "SELECT * FROM cirugia
				WHERE estado = 1 AND (
				   CAST(id_cirugia AS CHAR) LIKE :param_id
				   OR nombre_cirugia LIKE :param_nombre
				   OR gravedad LIKE :param_gravedad
				)
				ORDER BY nombre_cirugia
				LIMIT :limitacion OFFSET :offset";

		$query = $conex->prepare($sql);
		$query->bindParam(":param_id", $busqueda);
		$query->bindParam(":param_nombre", $busqueda);
		$query->bindParam(":param_gravedad", $busqueda);
		$query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
		$query->bindParam(":offset", $offset, \PDO::PARAM_INT);
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function actualizarCirugia(int $id, string $nombre, string $gravedad) {
		$this->id = $id;
		$this->nombre = $nombre;
		$this->gravedad = $gravedad;

		$conex = $this->getConexion();
		$sql = "UPDATE cirugia
				SET nombre_cirugia = :nombre, gravedad = :gravedad
				WHERE id_cirugia = :id";

		$query = $conex->prepare($sql);
		$query->bindParam(":id", $this->id, \PDO::PARAM_INT);
		$query->bindParam(":nombre", $this->nombre);
		$query->bindParam(":gravedad", $this->gravedad);

		return $query->execute();
	}

	public function eliminarCirugia(int $id) {
		$this->id = $id;
		$conex = $this->getConexion();
		$query = $conex->prepare("UPDATE cirugia SET estado = 0 WHERE id_cirugia = :id");
		$query->bindParam(":id", $this->id, \PDO::PARAM_INT);

		try {
			return $query->execute();
		} catch (\PDOException $exception) {
			return false;
		}
	}
}
