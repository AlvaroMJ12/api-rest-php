<?php
/**
 * Clase para el modelo que representa a la tabla "articulo".
 */
require_once 'src/response.php';
require_once 'src/database.php';

class Articulo extends Database {
	/**
	 * Atributo que indica la tabla asociada a la clase del modelo
	 */
	private $table = 'articulo';

	/**
	 * Atributo que indica la columna que es primary key en la tabla
	 */
	private $primary_key = 'art_id';


	/**
	 * Array con los campos de la tabla que se pueden usar como filtro para recuperar registros
	 */
	private $allowedConditions_get = array(
		'art_id',
		'art_nombre',
		'art_categoria',
		'art_cantidad'		
	);

	/**
	 * Array con los campos de la tabla que se pueden proporcionar para insertar registros
	 */
	private $allowedConditions_insert = array(
		'art_nombre',
		'art_categoria',
		'art_cantidad'	
	);

	/**
	 * Método para validar los datos que se mandan para insertar un registro, comprobar campos obligatorios, valores válidos, etc.
	 */
	private function validate($data) {

		if (!isset($data['art_nombre']) || empty($data['art_nombre'])) {
			$response = array(
				'result' => 'error',
				'details' => 'El campo articulo_name es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		return true;
	}

	/**
	 * Método para recuperar registros, pudiendo indicar algunos filtros 
	 */
	public function get($params) {
		foreach ($params as $key => $param) {
			if (!in_array($key, $this->allowedConditions_get)) {
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);

				Response::result(400, $response);
				exit;
			}
		}

		$articulos = parent::getDB($this->table, $params);

		return $articulos;
	}

	/**
	 * Método para guardar un registro en la base de datos, recibe como parámetro el JSON con los datos a insertar
	 */
	public function insert($params) {
		foreach ($params as $key => $param) {
			if (!in_array($key, $this->allowedConditions_insert)) {
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);

				Response::result(400, $response);
				exit;
			}
		}

		if ($this->validate($params)) {
			return parent::insertDB($this->table, $params);
		}
	}

	/**
	 * Método para actualizar un registro en la base de datos mediante PUT, se indica el id del registro que se quiere actualizar
	 */
	public function updatePut($art_id, $params) {
		foreach ($params as $key => $parm) {
			if (!in_array($key, $this->allowedConditions_insert)) {
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);

				Response::result(400, $response);
				exit;
			}
		}

		if ($this->validate($params)) {
			$affected_rows = parent::updateDB($this->table, $art_id, $this->primary_key, $params);

			if ($affected_rows == 0) {
				$response = array(
					'result' => 'error',
					'details' => 'No hubo cambios'
				);

				Response::result(200, $response);
				exit;
			}
		}
	}


	/**
	 * Método para actualizar un registro en la base de datos mediante PATCH, se indica el id del registro que se quiere actualizar
	 */
	public function updatePatch($art_id, $params) {
		foreach ($params as $key => $parm) {
			if (!in_array($key, $this->allowedConditions_insert)) {
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);

				Response::result(400, $response);
				exit;
			}
		}

		$affected_rows = parent::updateDB($this->table, $art_id, $this->primary_key, $params);

		if ($affected_rows == 0) {
			$response = array(
				'result' => 'error',
				'details' => 'No hubo cambios'
			);

			Response::result(200, $response);
			exit;
		}
	}


	/**
	 * Método para borrar un registro de la base de datos, se indica el id del registro que queremos eliminar
	 */
	public function delete($art_id) {
		$affected_rows = parent::deleteDB($this->table, $art_id, $this->primary_key);

		if ($affected_rows == 0) {
			$response = array(
				'result' => 'error',
				'details' => 'No hubo cambios'
			);

			Response::result(200, $response);
			exit;
		}
	}
}

?>
