<?php

use yii\db\Migration;

/**
 * Class m211118_122601_delete_column_role_to_user
 */
class m211118_122601_delete_column_role_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->dropColumn('user', 'role');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211118_122601_delete_column_role_to_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211118_122601_delete_column_role_to_user cannot be reverted.\n";

        return false;
    }
    */
}
