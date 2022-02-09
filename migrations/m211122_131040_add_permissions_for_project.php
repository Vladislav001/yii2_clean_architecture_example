<?php

use yii\db\Migration;
use \Adapters\Repositories\UserRepository;
use app\modules\rbac\rules\ChangeRulesProjectRule;

/**
 * Class m211122_131040_add_permissions_for_project
 */
class m211122_131040_add_permissions_for_project extends Migration
{
    /**
     * {@inheritdoc}
     */
	public function safeUp()
	{
		$UserRepository = new UserRepository();
		$userPermissions = $UserRepository->getPermissionsInfo();
		$userRoles = $UserRepository->getRolesInfo();
		$auth = \Yii::$app->authManager;

		$GetProject = $auth->createPermission($userPermissions['get_project']['key']);
		$GetProject->description = $userPermissions['get_project']['description'];
		$auth->add($GetProject);

		$ChangeRulesProjectRule = new ChangeRulesProjectRule();
		$changeRulesProject = $auth->createPermission($userPermissions['change_rules_project']['key']);
		$changeRulesProject->description = $userPermissions['change_rules_project']['description'];
		$changeRulesProject->ruleName = $ChangeRulesProjectRule->name;
		$auth->add($changeRulesProject);

		$user = Yii::$app->authManager->getRole($userRoles['user']['key']);
		$auth->addChild($user, $GetProject);
		$auth->addChild($user, $changeRulesProject);
	}

    /**
     * {@inheritdoc}
     */
	public function safeDown()
	{
		$UserRepository = new UserRepository();
		$userPermissions = $UserRepository->getPermissionsInfo();
		$userRoles = $UserRepository->getRolesInfo();
		$auth = \Yii::$app->authManager;

		$GetProject = $auth->getPermission($userPermissions['get_project']['key']);
		$auth->remove($GetProject);

		$ChangeRulesProject = $auth->getPermission($userPermissions['change_rules_project']['key']);
		$auth->remove($ChangeRulesProject);

		$user = Yii::$app->authManager->getRole($userRoles['user']['key']);
		$auth->removeChild($user, $GetProject);
		$auth->removeChild($user, $ChangeRulesProject);
	}

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211122_131040_add_permissions_for_project cannot be reverted.\n";

        return false;
    }
    */
}
