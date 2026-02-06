<?php
/**
 * Clase para trabajar con la autentificación en la API
 * Hace uso de las clases implementadas en la carpeta "jwt" para realizar la autentificación mediante token
 * El token se genera a partir del id del usuario, por lo que cada usuario tendrá siempre un token distinto. Además del id, para generar el token se hace uso de una clave secreta que es un atributo de la clase
 */
require_once 'jwt/JWT.php';
require_once 'src/authModel.php';
require_once 'src/response.php';
use Firebase\JWT\JWT;

class Authentication extends AuthModel
{
	/**
	 * Tabla donde estarán los usuarios
	 */
	private $table = 'usuario';

	/**
	 * Clave secreta para realizar la encriptación y desencriptación del token, se debería cambiar por una clave robusta
	 */
	private $key = 'clave_secreta';

	/**
	 * Método para que un usuario se autentifique con un nombre de usuario y una contraseña
	 */
	public function signIn($user)
	{
		if(!isset($user['username']) || !isset($user['password']) || empty($user['username']) || empty($user['password'])){
			$response = array(
				'result' => 'error',
				'details' => 'Los campos password y username son obligatorios'
			);
			
			Response::result(400, $response);
			exit;
		}
		
		$result = parent::login($user['username'], hash('sha256' , $user['password']));

		if(sizeof($result) == 0){
			$response = array(
				'result' => 'error',
				'details' => 'El usuario y/o la contraseña son incorrectas'
			);

			Response::result(403, $response);
			exit;
		}

		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $result[0]['id'],
				'nombres' => $result[0]['nombres']
			)
		);

		$jwt = JWT::encode($dataToken, $this->key);

		parent::update($result[0]['id'], $jwt);

		return $jwt;
	}

	/**
	 * Método para verificar si un token es válido cuando se realiza una petición a la API
	 * El token se manda como header poniendo en name "api-key" y como value el valor del token
	 */
	public function verify()
	{
		// 1. Obtenemos todas las cabeceras directamente del servidor 📡
		$allHeaders = apache_request_headers();
		
		// 2. Buscamos el token en 'Authorization' (estándar de Postman) o 'api-key' (tu versión anterior)
		$header = $allHeaders['Authorization'] ?? 
				$allHeaders['authorization'] ?? 
				$_SERVER['HTTP_AUTHORIZATION'] ?? 
				$_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? 
				$_SERVER['HTTP_API_KEY'] ?? // Mantenemos compatibilidad con tu versión previa
				null;

		if (!$header) {
			$response = array(
				'result' => 'error',
				'details' => 'No se detecta la cabecera de autorización. Verifique el envío del token.'
			);
			Response::result(403, $response);
			exit;
		}

		// 3. Postman envía "Bearer <token>". Separamos la palabra 'Bearer' del código real ✂️
		$partes = explode(" ", $header);
		
		// Si hay dos partes, el token es la segunda; si no, es la primera.
		$jwt = (count($partes) === 2) ? $partes[1] : $partes[0];

		try {
			// 4. Intentamos decodificar el token con la librería JWT
			$data = JWT::decode($jwt, $this->key, array('HS256'));

			// 5. Consultamos al modelo para ver si el usuario existe
			$user = parent::getById($data->data->id);

			// 6. Comprobamos que el token de la BD coincida con el recibido
			if (empty($user) || $user[0]['token'] != $jwt) {
				throw new Exception("Token no coincide en la base de datos");
			}
			
			return $data; // Todo ok, devolvemos los datos del usuario

		} catch (\Throwable $th) {
			// Si el token ha expirado, está mal firmado o no coincide 🚫
			$response = array(
				'result' => 'error',
				'details' => 'Token inválido, expirado o sin permisos'
			);
			Response::result(403, $response);
			exit;
		}
	}
	
}
