<?php
/**
 * Clase para el modelo que representa a la tabla "player".
 */
require_once 'src/response.php';
require_once 'src/database.php';

class Categoria extends Database {
	/**
	 * Atributo que indica la tabla asociada a la clase del modelo
	 */
	private $table = 'categoria';

	/**
	 * Atributo que indica la columna que es primary key en la tabla
	 */
	private $primary_key = 'cat_id';


	/**
	 * Array con los campos de la tabla que se pueden usar como filtro para recuperar registros
	 */
	private $allowedConditions_get = array(
		'cat_id',
		'cat_nombre',
	);

	/**
	 * Array con los campos de la tabla que se pueden proporcionar para insertar registros
	 */
	private $allowedConditions_insert = array(
		'cat_nombre',
	);

	/**
	 * Método para valcat_idar los datos que se mandan para insertar un registro, comprobar campos obligatorios, valores válcat_idos, etc.
	 */
	private function validate($data) {

		if (!isset($data['cat_nombre']) || empty($data['cat_nombre'])) {
			$response = array(
				'result' => 'error',
				'details' => 'El campo cat_nombre es obligatorio'
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

		$categorias = parent::getDB($this->table, $params);

		return $categorias;
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
	 * Método para actualizar un registro en la base de datos mediante PUT, se indica el cat_id del registro que se quiere actualizar
	 */
	public function updatePut($cat_id, $params) {
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
			$affected_rows = parent::updateDB($this->table, $cat_id, $this->primary_key, $params);

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
	 * Método para actualizar un registro en la base de datos mediante PATCH, se indica el cat_id del registro que se quiere actualizar
	 */
	public function updatePatch($cat_id, $params) {
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

		$affected_rows = parent::updateDB($this->table, $cat_id, $this->primary_key, $params);

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
	 * Método para borrar un registro de la base de datos, se indica el cat_id del registro que queremos eliminar
	 */
	public function delete($cat_id) {

        $condiciones = [
            "art_categoria" => "$cat_id"
        ];
        
        $id_cat_articulo = $this->getDB("articulo", $condiciones);

        if(!empty($id_cat_articulo)){
            $response = array(
            'result' => 'error',
            'details' => 'No se puede borrar la categoría porque tiene artículos asociados.'
            );

            Response::result(200, $response);
            exit;
        }

		$affected_rows = parent::deleteDB($this->table, $cat_id, $this->primary_key);

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
