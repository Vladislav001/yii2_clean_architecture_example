<?php

use yii\db\Migration;

/**
 * Class m211116_092211_update_table_user
 */
class m211116_092211_update_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->renameColumn('user', 'password_hash', 'password');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->renameColumn('user', 'password', 'password_hash');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211116_092211_update_table_user cannot be reverted.\n";

        return false;
    }
    */
}
