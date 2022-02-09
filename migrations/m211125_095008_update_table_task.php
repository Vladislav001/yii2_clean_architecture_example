<?php

use yii\db\Migration;

/**
 * Class m211125_095008_update_table_task
 */
class m211125_095008_update_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn ('task', 'number', $this->integer(11)->comment('Номер в таблице excel'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('task', 'number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211125_095008_update_table_task cannot be reverted.\n";

        return false;
    }
    */
}
