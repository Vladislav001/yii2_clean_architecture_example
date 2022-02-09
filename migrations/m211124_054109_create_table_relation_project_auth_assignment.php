<?php

use yii\db\Migration;

/**
 * Class m211124_054109_create_table_relation_project_auth_assignment
 */
class m211124_054109_create_table_relation_project_auth_assignment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('relation_project_auth_item', [
			'project_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
			'user_id' => $this->integer()->notNull(),
		]);

		$this->addPrimaryKey('project_name_user_pk', 'relation_project_auth_item', ['project_id', 'name', 'user_id']);

		$this->addForeignKey(
			'project_id_in_relation_project_auth_item',  // это "условное имя" ключа
			'relation_project_auth_item', // это название текущей таблицы
			'project_id', // это имя поля в текущей таблице, которое будет ключом
			'project', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'CASCADE'
		);

		$this->addForeignKey(
			'user_id_in_relation_project_auth_item',  // это "условное имя" ключа
			'relation_project_auth_item', // это название текущей таблицы
			'user_id', // это имя поля в текущей таблице, которое будет ключом
			'user', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'CASCADE'
		);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropForeignKey(
			'project_id_in_relation_project_auth_item',
			'relation_project_auth_item'
		);

		$this->dropForeignKey(
			'user_id_in_relation_project_auth_item',
			'relation_project_auth_item'
		);

		$this->dropTable('relation_project_auth_assignment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211124_054109_create_table_relation_project_auth_assignment cannot be reverted.\n";

        return false;
    }
    */
}
