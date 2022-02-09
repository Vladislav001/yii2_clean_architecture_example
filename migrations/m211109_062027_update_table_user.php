<?php

use yii\db\Migration;

/**
 * Class m211109_062027_update_table_user
 */
class m211109_062027_update_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn('user', 'role', 'integer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->alterColumn('user', 'role', 'string');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211109_062027_update_table_user cannot be reverted.\n";

        return false;
    }
    */
}
