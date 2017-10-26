<?php

/**
 * API Logic
 *
 * @link       http://codex.management
 * @since      1.0.0
 *
 * @package    Codex_Connector
 * @subpackage Codex_Connector/api
 */

/**
 *
 *
 * This class defines all code necessary to run the api.
 *
 * @since      1.0.0
 * @package    Codex_Connector
 * @subpackage Codex_Connector/api
 * @author     Daan Rijpkema <info@codex.management>
 */
class Codex_Connector_API {

	static $authenticated = false;
	static $username;
	private static $password;

	static $server;
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function initiate($server,$username,$password) {
		
		self::$server = $server;
		self::$username = $username;
		self::$password = $password;
		
		if(!session_id()) {
			session_start();
		}
	}
	/**
	 * Make a generic API call to the Codex API
	 * @param  string $method    The method, either GET or POST
	 * @param  string $_endpoint The endpoint in a string
	 * @param bool $auth If auth is necessary
	 * @param  array  $params    A list of key-value pair parameters for either GET or POST request bodys
	 * @param  array  $_headers  A list of HTTP headers to add
	 * @return response            Result
	 */
	public static function api_call($method="GET",$_endpoint="index",$auth = true,$params = [],$_headers = [],$last_try = false)
	{


		$server_base_url = self::$server;
		// var_dump($server_base_url);
		if(!$server_base_url || $server_base_url == "") {
			return ['status'=>false,'error'=>'API is not configured correctly; servername is missing'];
		}

		$endpoint = "https://{$server_base_url}.codex.link/api/{$_endpoint}";
		

		$paramstring = "";
		foreach ($params as $p_key => $p_value) {

			$paramstring.="{$p_key}=".urlencode($p_value);
			if($params[$p_key]!== end($params))
			{
				$paramstring .="&";
			}
		}
		
		$headers = array_merge($_headers,array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",
			));
		
		// Adding token header
		if($auth) {
			if(!isset($_SESSION['codex_api_token'])) {
				// make auth call first
				Codex_Connector_API::authenticate();
			}
			
			$headers[] = "Authorization: Bearer {$_SESSION['codex_api_token']}";
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL =>$endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => strtoupper($method),
			CURLOPT_POSTFIELDS => $paramstring,
			CURLOPT_HTTPHEADER => $headers,
			));


		$json_response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return false;
			// throw new Exception("Error making API call", 400);
		} else {
			try {
				$php_response = json_decode($json_response);
			} catch (Exception $e) {
				return (object) ['status'=>false,'error'=>"An error occured connecting to Codex (error decoding response to endpoint {$endpoint})"];
				
				
			}


			if(isset($php_response->error))
			{
				if(!$auth) {
					// normal error response handling:
					return (object) ['status'=>false,'error'=>"An error occured connecting to Codex (".$php_response->error.")"];
				}	
				else {
						// if response = no auth, auth again and try ONCE more
					// if($php_response->error =="Authorization header is empty or invalid")
					// {
						if(!$last_try) { 
							self::authenticate();
								// but only do it once, so set $last_try to true
							self::api_call($method,$_endpoint,$auth,$params,$_headers,true);
							
						} else {

							return (object) ['status'=>false,'error'=>"Error authorizing and we stopped trying to connect."];
						}
					// } else {
					// 	return (object) ['status'=>false,'error'=>"Problem requesting Codex:". ($php_response->error)];
					// }
				}

				return (object) ['status'=>false,'error'=>"An error occured requesting from Codex (".$php_response->error.")"];
			}




			return $php_response;
		}
		// @todo: error handling
	}

	public static function authenticate()
	{
		if(isset(self::$username) && isset(self::$password))
		{
			
			$response = Codex_Connector_API::api_call(
				'post',
				'authenticate',
				false,
				[
				'email'=>self::$username,
				'password'=>self::$password
				]
				);
			
			if(is_null($response) || $response === false)
			{
				return ['status'=>false,'error'=>'Could not authenticate: received no response'];

			}
			if(

				isset($response->error)
				)
			{

				return ['status'=>false,'error'=>'Could not authenticate: received an error: '.$response->error];
			}
			
			if(!session_id()) {
				session_start();
			}

			// refresh the api token in session
			unset($_SESSION['codex_api_token']);
			$_SESSION['codex_api_token'] = $response->token;

			self::$authenticated = true;

			return ['status'=>true,'response'=>$response];

		}
		return ['status'=>false,'error'=>'Could not authenticate: no username and/or pass set'];
	}

}

