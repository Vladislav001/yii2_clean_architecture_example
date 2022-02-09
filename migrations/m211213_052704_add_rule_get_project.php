<?php

use yii\db\Migration;
use app\modules\rbac\rules\GetProjectRule;
use \Adapters\Repositories\UserRepository;

/**
 * Class m211213_052704_add_rule_get_project
 */
class m211213_052704_add_rule_get_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$auth = \Yii::$app->authManager;

		$UserRepository = new UserRepository();
		$userPermissions = $UserRepository->getPermissionsInfo();

		$rule = new GetProjectRule;
		$auth->add($rule);

		$GetProject = $auth->getPermission($userPermissions['get_project']['key']);
		$GetProject->ruleName = $rule->name;
		$auth->update($userPermissions['get_project']['key'], $GetProject);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$auth = \Yii::$app->authManager;

		$UserRepository = new UserRepository();
		$userPermissions = $UserRepository->getPermissionsInfo();

		$rule = new GetProjectRule;
		$auth->remove($rule);

		$GetProject = $auth->getPermission($userPermissions['get_project']['key']);
		$GetProject->ruleName = NULL;
		$auth->update($userPermissions['get_project']['key'], $GetProject);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211213_052704_add_rule_get_project cannot be reverted.\n";

        return false;
    }
    */
}
