<?

namespace FrameworksDrivers;

class ErrorService
{
	protected $errors = array();
	public $ERROR_DICTIONARY = array(
		'error' => array("Some Error", ""),
		'database_error' => array("Database error", ""),
		'invalid_data' => array("Invalid data"),
		"token_is_not_valid" => array("Error", "Token is not valid"),
		"token_expired" => array("Error", "Token is expired"),
		"user_not_found" => array("Error", "User not found"),
		'email_is_not_valid' => array("Email is not valid"),
		'email_or_password_is_not_valid' => array("Email or password is not valid"),
		'forbidden' => array("Forbidden"),

		'create_permission' => array("Failed to add permission"),
		'delete_permission' => array("Failed to delete permission"),

		"project_not_found" => array("Error", "Project not found"),

		"direction_not_found" => array("Error", "Direction not found"),
		"direction_forbidden_delete_archive" => array("Error", "It is forbidden to delete the archive"),

		"task_not_found" => array("Error", "Task not found"),
		"task_status_not_found" => array("Error", "Task status not found"),
		"task_type_not_found" => array("Error", "Task type not found"),
		"task_responsible_not_found" => array("Error", "Task responsible not found"),
		"task_already_in_direction_archive" => array("Error", "The task is already in the archive"),
	);

	public function addError(string $errorId)
	{
		$this->errors[] = array(
			'id' => $errorId,
			'title' => $this->ERROR_DICTIONARY[$errorId][0],
			'detail' => $this->ERROR_DICTIONARY[$errorId][1] ? : '',
		);
	}

	public function addErrors(array $errorIDs)
	{
		foreach ($errorIDs as $errorId)
		{
			$this->errors[] = array(
				'id' => $errorId,
				'title' => $this->ERROR_DICTIONARY[$errorId][0],
				'detail' => $this->ERROR_DICTIONARY[$errorId][1] ? : '',
			);
		}
	}

	public function getErrors()
	{
		return $this->errors;
	}
}