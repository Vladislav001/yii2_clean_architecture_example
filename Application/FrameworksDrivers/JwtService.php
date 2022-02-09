<?

namespace FrameworksDrivers;

use Firebase\JWT\JWT;

class JwtService
{
	private $key = "
		IXXR7gmCBZ4zgcuwuRjyZJ2g23IJS0mFs9394yBeBPLyoGj8twE5YSB4ewssoNY7GzRnLq5saCPKVLBTX
		43Cw5ef0YGAS95jOPBraUIa78CUh0JnXpCiwTiWNU34e9Gjn9oxxXDnp56vfCRRZw0zwMcNYhK9xXJVR9
		SLbd7G4u9asaIFC6ObwlMoCEz3rXQDiON8baZxZZX4yhjizKgJuWv5kKiVtVRcN8ZnpvqAloKB6FAnPSS8rQVNzJux2mq
		";
	private $expAccessToken = ACCESS_TOKEN_LIFE_TIME;
	private $expRefreshToken = REFRESH_TOKEN_LIFE_TIME;
	private $expPasswordResetToken = PASSWORD_RESET_TOKEN_LIFE_TIME;

	public function CreateNewAccessToken($userId)
	{
		$payload = array(
			"iss" => $_SERVER['SERVER_NAME'],
			"iat" => time(),
			"nbf" => time(),
			"exp" => time() + $this->expAccessToken,
			'data' => [
				'user_id' => $userId,
				'rand_data' => time() . rand(6,10)
			]
		);

		$jwt = JWT::encode($payload, $this->key);

		return $jwt;
	}

	public function CreateNewRefreshToken($userId)
	{
		$payload = array(
			"iss" => $_SERVER['SERVER_NAME'],
			"iat" => time(),
			"nbf" => time(),
			"exp" => time() + $this->expRefreshToken,
			'data' => [
				'user_id' => $userId,
				'rand_data' => time() . rand(6,10)
			]
		);

		$jwt = JWT::encode($payload, $this->key);

		return $jwt;
	}

	public function CreateNewPasswordResetToken($userId)
	{
		$payload = array(
			"iss" => $_SERVER['SERVER_NAME'],
			"iat" => time(),
			"nbf" => time(),
			"exp" => time() + $this->expPasswordResetToken,
			'data' => [
				'user_id' => $userId,
			]
		);

		$jwt = JWT::encode($payload, $this->key);

		return $jwt;
	}

	public function DecodeToken($token)
	{
		try
		{
			$decoded = JWT::decode($token, $this->key, array('HS256'));
			$decodedArray = json_decode(json_encode($decoded), true);

			return $decodedArray;
		} catch (Exception $e)
		{
			return array("exception" => $e);
		}
	}
}