<?php

use yii\db\Migration;

/**
 * Class m211109_055439_create_table_user
 */
class m211109_055439_create_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('user', [
			'id' => $this->primaryKey(),
			'email' => $this->string()->notNull()->unique(),
			'name' => $this->string()->notNull(),
			'password_hash' => $this->string()->notNull(),
			'role' => $this->string()->notNull()->defaultValue(2),
			'created_at' => $this->dateTime(),
			'updated_at' => $this->dateTime()->null(),
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211109_055439_create_table_user cannot be reverted.\n";

        return false;
    }
    */
}
