<?php
/**
 * Clase con la lógica para conectarse a la base de datos. 
 * Incluye métodos para recuperar registros, actualizar y borrarlos de cualquier tabla de la base de datos, además de poder filtrar las consultas.
 */
class Database
{
	private $connection;
	/**
	 * Atributo que indica la cantidad de registros por página a la hora de recuperar datos
	 */
	private $results_page = 50;

	public function __construct(){
		$this->connection = new mysqli('db', 'root', 'root', 'almacen', '3306');

		if($this->connection->connect_errno){
			echo 'Error de conexión a la base de datos';
			exit;
		}
	}

	/**
	 * Método para recuperar datos de una tabla, pudiendo indicar filtros con el parámetro $extra
	 */
	public function getDB($table, $extra = null)
	{
		$page = 0;
		$query = "SELECT * FROM $table";

		if(isset($extra['page'])){
			$page = $extra['page'];
			unset($extra['page']);
		}

		if($extra != null){
			$query .= ' WHERE';

			foreach ($extra as $key => $condition) {
				$query .= ' '.$key.' = "'.$condition.'"';
				if($extra[$key] != end($extra)){
					$query .= " AND ";
				}
			}
		}

		/**
		 * Aquí se paginan los resultados para evitar recuperar todos los registros de una tabla que contenga muchísimos
		 */
		if($page > 0){
			$since = (($page-1) * $this->results_page);
			$query .= " LIMIT $since, $this->results_page";
		}
		else{
			$query .= " LIMIT 0, $this->results_page";
		}

		$results = $this->connection->query($query);
		$resultArray = array();

		foreach ($results as $value) {
			$resultArray[] = $value;
		}

		return $resultArray;
	}

	/**
	 * Método para insertar un nuevo registro
	 */
	/**
     * Método para insertar un nuevo registro
     */
    public function insertDB($table, $data)
    {
        // PASO 1: Escapar los datos para evitar errores de comillas
        // Recorremos el array y limpiamos cada valor
        $cleanData = [];
        foreach ($data as $key => $value) {
            $cleanData[$key] = $this->connection->real_escape_string($value);
        }

        // PASO 2: Construir la consulta con los datos limpios
        $fields = implode(',', array_keys($cleanData));
        $values = '"' . implode('","', array_values($cleanData)) . '"';

        $query = "INSERT INTO $table ($fields) VALUES ($values)";

        // PASO 3: Ejecutar la consulta con manejo de errores
        try {
            $result = $this->connection->query($query);

            // Si por alguna razón devuelve false sin lanzar excepción
            if (!$result) {
                // Forzamos que se muestre el error en la respuesta JSON
                $response = array(
                    'result' => 'error',
                    'details' => 'Error SQL: ' . $this->connection->error
                );
                Response::result(500, $response);
                exit;
            }

            return $this->connection->insert_id;

        } catch (Exception $e) {
            // Si hay un error fatal (ej: clave duplicada), lo capturamos aquí
            $response = array(
                'result' => 'error',
                'details' => 'Excepción en BD: ' . $e->getMessage()
            );
            Response::result(500, $response);
            exit;
        }
    }

	/**
	 * Método para actualizar un registro de la BD
	 * Hay que indicar el registro mediante un campo que sea clave primaria y que debe llamarse "id"
	 * El parámetro "pk" indica la columna de la tabla que es primary key
	 */
	public function updateDB($table, $id, $pk, $data)
	{	
		$query = "UPDATE $table SET ";
		foreach ($data as $key => $value) {
			$query .= "$key = '$value'";
			if(sizeof($data) > 1 && $key != array_key_last($data)){
				$query .= " , ";
			}
		}

		$query .= ' WHERE '. $pk . ' = '.$id;

		$this->connection->query($query);

		if(!$this->connection->affected_rows){
			return 0;
		}

		return $this->connection->affected_rows;
	}

	/**
	 * Método para eliminar un registro de la BD
	 * Hay que indicar el registro mediante un campo que sea clave primaria y que debe llamarse "id"
	 * El parámetro "pk" indica la columna de la tabla que es primary key
	 */
	public function deleteDB($table, $id, $pk)
	{
		$query = "DELETE FROM $table WHERE $pk = $id";
		$this->connection->query($query);

		if(!$this->connection->affected_rows){
			return 0;
		}

		return $this->connection->affected_rows;
	}
}


?>
