<?php

use yii\db\Migration;

/**
 * Class m211118_054128_add_column_refresh_token_to_user
 */
class m211118_054128_add_column_refresh_token_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn ('user', 'refresh_token', $this->string(255)->defaultValue (NULL));
		$this->addColumn ('user', 'refresh_token_expire_at', $this->integer(11)->defaultValue (NULL)->comment('Срок действия токена'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211118_054128_add_column_refresh_token_to_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211118_054128_add_column_refresh_token_to_user cannot be reverted.\n";

        return false;
    }
    */
}
