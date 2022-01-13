<?php

namespace app\modules\rbac\rules;

use yii\rbac\Rule;
use \Adapters\Repositories\UserRepository;
use \Adapters\Repositories\ProjectRepository;

/**
 * Проверяем, что добавить права на проект может либо админ, либо создатель проекта
 */
class ChangeRulesProjectRule extends Rule
{
	public $name = 'rule_' . UserRepository::PERMISSIONS['change_rules_project']['key'];

	/**
	 * @param string|int $user the user ID.
	 * @param Item $item the role or permission that this rule is associated width.
	 * @param array $params parameters passed to ManagerInterface::checkAccess().
	 * @return bool a value indicating whether the rule permits the role or permission it is associated with.
	 */
	public function execute($userId, $item, $params)
	{
		$UserRepository = new UserRepository();
		$userRoles = $UserRepository->getRolesInfo();
		$allowedRoleName = $userRoles['admin']['key'];

		$assignment = \Yii::$app->authManager->getAssignment($allowedRoleName, $userId);
		$isAdmin = $assignment !== null ? true : false;

		if ($isAdmin)
		{
			return true;
		}

		$ProjectRepository = new ProjectRepository();
		$project = $ProjectRepository->find(array("where" => array(
			'id' => $params['project_id']
		)))[0];

		if ($project['creator_id'] == $userId)
		{
			return true;
		}

		return false;
	}
}