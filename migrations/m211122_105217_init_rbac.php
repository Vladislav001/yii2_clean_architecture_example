<?php

use yii\db\Migration;
use \Adapters\Repositories\UserRepository;

/**
 * Class m211122_105217_init_rbac
 */
class m211122_105217_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$UserRepository = new UserRepository();
		$userRoles = $UserRepository->getRolesInfo();

		$auth = \Yii::$app->authManager;
		$user = $auth->createRole($userRoles['user']['key']);
		$user->description = $userRoles['user']['description'];
		$auth->add($user);
		$admin = $auth->createRole($userRoles['admin']['key']);
		$admin->description = $userRoles['admin']['description'];
		$auth->add($admin);
		$auth->addChild($admin, $user);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$UserRepository = new UserRepository();
		$userRoles = $UserRepository->getRolesInfo();

		$auth = \Yii::$app->authManager;
		$user = $auth->getRole($userRoles['user']['key']);
		$admin = $auth->getRole($userRoles['admin']['key']);

		$auth->removeChild($admin, $user);
		$auth->remove($user);
		$auth->remove($admin);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211122_105217_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
