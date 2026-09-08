<?php
namespace Gabriel\SistemaFarmovet\model;

use Gabriel\SistemaFarmovet\config\ConexionBD;

class MedicamentoModel extends ConexionBD {
	private int $id;
	private string $nombre;
	private int $tipo;
    private int $presentacion;
    private int $estado;

	public function agregarMedicamento(string $nombre,int $tipo, int $presentacion) {
		$this->nombre = $nombre;
        $this->tipo = $tipo;
		$this->presentacion = $presentacion;

		$conex = $this->getConexion();
		$sql = "INSERT INTO medicamento (nombre_medicamento, id_tipo_medicamento,id_presentacion, estado)
				VALUES (:nombre, :tipo, :presentacion, 1)";

		$query = $conex->prepare($sql);
		$query->bindParam(":nombre", $this->nombre);
		$query->bindParam(":tipo", $this->tipo);
        $query->bindParam(":presentacion", $this->presentacion);

		return $query->execute();
	}

	public function obtenerMedicamento(int $pagina, int $limitacion) {
		$offset = ($pagina * $limitacion) - $limitacion;
		$conex = $this->getConexion();
		$sql = "SELECT * FROM medicamento
				INNER JOIN tipo_medicamento ON medicamento.id_tipo_medicamento = tipo_medicamento.id_tipo_medicamento
				INNER JOIN presentacion ON medicamento.id_presentacion = presentacion.id_presentacion
				WHERE medicamento.estado = 1
				LIMIT :limitacion OFFSET :offset";

		$query = $conex->prepare($sql);
		$query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
		$query->bindParam(":offset", $offset, \PDO::PARAM_INT);
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function obtenerMedicamentoPorId(int $id) {
		$this->id = $id;
		$conex = $this->getConexion();
		$sql = "SELECT * FROM medicamento
				INNER JOIN tipo_medicamento ON medicamento.id_tipo_medicamento = tipo_medicamento.id_tipo_medicamento
				INNER JOIN presentacion ON medicamento.id_presentacion = presentacion.id_presentacion
				WHERE medicamento.id_medicamento = :id AND medicamento.estado = 1";
		$query = $conex->prepare($sql);
		$query->bindParam(":id", $this->id, \PDO::PARAM_INT);
		$query->execute();

		return $query->fetch(\PDO::FETCH_ASSOC);
	}

	public function filtrarMedicamento(string $param, int $pagina, int $limitacion) {
		$offset = ($pagina * $limitacion) - $limitacion;
		$busqueda = $param . "%";
		$conex = $this->getConexion();
		$sql = "SELECT * FROM medicamento
				INNER JOIN tipo_medicamento ON medicamento.id_tipo_medicamento = tipo_medicamento.id_tipo_medicamento
				INNER JOIN presentacion ON medicamento.id_presentacion = presentacion.id_presentacion
				WHERE medicamento.estado = 1 AND (
				   CAST(medicamento.id_medicamento AS CHAR) LIKE :param
				   OR medicamento.nombre_medicamento LIKE :param
				   OR medicamento.id_tipo_medicamento LIKE :param
				)
				LIMIT :limitacion OFFSET :offset";

		$query = $conex->prepare($sql);
		$query->bindParam(":param", $busqueda);
		$query->bindParam(":limitacion", $limitacion, \PDO::PARAM_INT);
		$query->bindParam(":offset", $offset, \PDO::PARAM_INT);
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function actualizarMedicamento(int $id, string $nombre,int $tipo, int $presentacion) {
		$this->id = $id;
		$this->nombre = $nombre;
        $this->tipo = $tipo;
		$this->presentacion = $presentacion;

		$conex = $this->getConexion();
		$sql = "UPDATE medicamento
				SET nombre_medicamento = :nombre, id_tipo_medicamento = :tipo, id_presentacion  = :presentacion
				WHERE id_medicamento = :id AND estado = 1";

		$query = $conex->prepare($sql);
		$query->bindParam(":id", $this->id, \PDO::PARAM_INT);
		$query->bindParam(":nombre", $this->nombre);
		$query->bindParam(":tipo", $this->tipo);
        $query->bindParam(":presentacion", $this->presentacion);

		return $query->execute();
	}

	public function eliminarMedicamento(int $id) {
		$this->id = $id;
		$conex = $this->getConexion();
		$query = $conex->prepare("UPDATE medicamento SET estado = 0 WHERE id_medicamento = :id");
		$query->bindParam(":id", $this->id, \PDO::PARAM_INT);

			return $query->execute();
		
}
   public function obtenerCatalogoTipos() {
		$conex = $this->getConexion();
		$sql = "SELECT id_tipo_medicamento, nom_tipo_medicamento
				FROM tipo_medicamento
				WHERE estado = 1
				ORDER BY nom_tipo_medicamento";
		$query = $conex->prepare($sql);
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function obtenerCatalogoPresentaciones() {
		$conex = $this->getConexion();
		$sql = "SELECT id_presentacion, nombre_presentacion
				FROM presentacion
				WHERE estado = 1
				ORDER BY nombre_presentacion";
		$query = $conex->prepare($sql);
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
}