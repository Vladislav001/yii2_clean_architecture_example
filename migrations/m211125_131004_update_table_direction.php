<?php

use yii\db\Migration;

/**
 * Class m211125_131004_update_table_direction
 */
class m211125_131004_update_table_direction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn ('direction', 'number', $this->integer(11)->comment('Порядок в таблице excel'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('direction', 'number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211125_131004_update_table_direction cannot be reverted.\n";

        return false;
    }
    */
}
