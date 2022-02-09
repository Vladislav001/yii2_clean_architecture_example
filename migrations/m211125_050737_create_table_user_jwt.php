<?php

use yii\db\Migration;

/**
 * Class m211125_050737_create_table_user_jwt
 */
class m211125_050737_create_table_user_jwt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('user_jwt', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'access_token' => $this->string(10000)->defaultValue(NULL),
			'access_token_expire_at' => $this->integer(11)->defaultValue(NULL),
			'refresh_token' => $this->string(10000)->defaultValue (NULL),
			'refresh_token_expire_at' => $this->integer(11)->defaultValue(NULL),
		]);

		$this->addForeignKey(
			'user_id',  // это "условное имя" ключа
			'user_jwt', // это название текущей таблицы
			'user_id', // это имя поля в текущей таблице, которое будет ключом
			'user', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'CASCADE'
		);

		$this->dropColumn('user', 'access_token');
		$this->dropColumn('user', 'access_token_expire_at');
		$this->dropColumn('user', 'refresh_token');
		$this->dropColumn('user', 'refresh_token_expire_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropForeignKey(
			'user_id',
			'user_jwt'
		);

		$this->dropTable('user_jwt');

		$this->addColumn ('user', 'access_token', $this->string(255)->defaultValue (NULL));
		$this->addColumn ('user', 'access_token_expire_at', $this->integer(11)->defaultValue (NULL)->comment('Срок действия токена'));

		$this->addColumn ('user', 'refresh_token', $this->string(255)->defaultValue (NULL));
		$this->addColumn ('user', 'refresh_token_expire_at', $this->integer(11)->defaultValue (NULL)->comment('Срок действия токена'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211125_050737_create_table_user_jwt cannot be reverted.\n";

        return false;
    }
    */
}
