<?php

use yii\db\Migration;

/**
 * Class m211116_093304_update_table_user
 */
class m211116_093304_update_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn('user','role', $this->integer()->notNull()->defaultValue(2));
		$this->alterColumn('user','created_at', $this->dateTime()->notNull()->defaultValue(new \yii\db\Expression('NOW()')));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211116_093304_update_table_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211116_093304_update_table_user cannot be reverted.\n";

        return false;
    }
    */
}
