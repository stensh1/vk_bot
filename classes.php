<?php

class Soket
{
	private const CONFIRMATION_TOKEN = 'some_conf_token';
	private const PUBLIC_TOKEN = 'some_pub_token';
	private const SECRET_KEY = 'some_secret_key';
	private const CURRENT_API = '5.80'; /* optional */
	
	public function Handler()
	{
		$data = json_decode(file_get_contents('php://input'));
		
		if(strcmp($data->secret, self::SECRET_KEY) !== 0)
			return;
		
		switch($data->type)
		{
			case 'confirmation':
				echo self::CONFIRMATION_TOKEN;
				exit();
				break;
		}
	}
	
	public function Send_Msg($text)
	{
		$request_params = array( 
			'message' => $text,  
			'peer_id' => 2000000001, /* cuz Im usin it only for one chat group */
			'access_token' => self::PUBLIC_TOKEN, 
			'v' => self::CURRENT_API
			);
				
			$get_params = http_build_query($request_params); 

			file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); 

			echo('ok'); 
	}
}

?>