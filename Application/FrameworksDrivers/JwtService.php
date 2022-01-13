<?

namespace FrameworksDrivers;

use Firebase\JWT\JWT;

class JwtService
{
	private $key = "
		
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