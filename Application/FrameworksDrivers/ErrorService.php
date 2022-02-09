<?

namespace FrameworksDrivers;

class ErrorService
{
	protected $errors = array();

	/*
	 * @see https://restapitutorial.ru/httpstatuscodes.html
	 * @see https://zametkinapolyah.ru/servera-i-protokoly/http-kody-oshibok-klienta.html
	 * @see https://great-world.ru/kody-otvetov-servera-i-oshibki-http-200-301-404-302-500-503-550/
	 */
	public $ERROR_DICTIONARY = array(
		'error' => array("Some Error", "", 500),
		'database_error' => array("Database error", "", 500),
		'di_error' => array("Some error", "", 500),
		'type_error' => array("Type error", "", 500),
		'invalid_data' => array("Invalid data", "", 500),
		"token_is_not_valid" => array("Token is not valid", "", 400),
		"token_expired" => array("Token is expired", "", 400),
		"user_not_found" => array("User not found", "", 401),
		'email_is_not_valid' => array("Email is not valid", "", 400),
		'email_or_password_is_not_valid' => array("Email or password is not valid", "", 400),
		'forbidden' => array("Forbidden"),
		"access_is_denied" => array("Access is denied", "", 403),

		'create_permission' => array("Failed to add permission", "", 500),
		'delete_permission' => array("Failed to delete permission", "", 500),

		"project_not_found" => array("Error", "Project not found", 404),

		"direction_not_found" => array("Error", "Direction not found", 404),
		"direction_forbidden_delete_archive" => array("Error", "It is forbidden to delete the archive", 403),

		"task_not_found" => array("Error", "Task not found", 404),
		"task_status_not_found" => array("Error", "Task status not found", 404),
		"task_type_not_found" => array("Error", "Task type not found", 404),
		"task_responsible_not_found" => array("Error", "Task responsible not found", 404),
		"task_already_in_direction_archive" => array("Error", "The task is already in the archive", 400),
	);

	public function addError(string $errorId)
	{
		$this->errors[] = array(
			'id' => $errorId,
			'title' => $this->ERROR_DICTIONARY[$errorId][0],
			'detail' => $this->ERROR_DICTIONARY[$errorId][1] ? : '',
			'http_code' =>  $this->ERROR_DICTIONARY[$errorId][2],
		);
	}

	public function getErrors()
	{
		return $this->errors;
	}
}