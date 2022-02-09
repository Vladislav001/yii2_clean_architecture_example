<?php

use yii\db\Migration;
use app\modules\rbac\rules\ChangeRulesProjectRule;

/**
 * Class m211122_130938_add_rules
 */
class m211122_130938_add_rules extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$auth = \Yii::$app->authManager;

		$rule = new ChangeRulesProjectRule;
		$auth->add($rule);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$auth = \Yii::$app->authManager;
		$rule = new ChangeRulesProjectRule;
		$auth->remove($rule);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211122_130938_add_rules cannot be reverted.\n";

        return false;
    }
    */
}
