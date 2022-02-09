<?php

use yii\db\Migration;

/**
 * Class m211116_100237_add_column_access_token_to_user
 */
class m211116_100237_add_column_access_token_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn ('user', 'access_token', $this->string(255)->defaultValue (NULL));
		$this->addColumn ('user', 'access_token_expire_at', $this->integer(11)->defaultValue (NULL)->comment('Срок действия токена'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('user', 'access_token');
		$this->dropColumn('user', 'access_token_expire_at');

		return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211116_100237_add_column_access_token_to_user cannot be reverted.\n";

        return false;
    }
    */
}
