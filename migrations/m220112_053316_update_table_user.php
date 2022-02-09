<?php

use yii\db\Migration;

/**
 * Class m220112_053316_update_table_user
 */
class m220112_053316_update_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('user','password_reset_token', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('user', 'password_reset_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220112_053316_update_table_user cannot be reverted.\n";

        return false;
    }
    */
}
