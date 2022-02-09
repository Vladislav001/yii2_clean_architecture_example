<?php

use yii\db\Migration;

/**
 * Class m211209_130749_create_tables_type_status_responsible
 */
class m211209_130749_create_tables_type_status_responsible extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('task_status', [
			'id' => $this->primaryKey(),
			'project_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),

		]);
		$this->addForeignKey(
			'task_status_project_id',  // это "условное имя" ключа
			'task_status', // это название текущей таблицы
			'project_id', // это имя поля в текущей таблице, которое будет ключом
			'project', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'CASCADE'
		);

		$this->createTable('task_type', [
			'id' => $this->primaryKey(),
			'project_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
		]);
		$this->addForeignKey(
			'task_type_project_id',  // это "условное имя" ключа
			'task_type', // это название текущей таблицы
			'project_id', // это имя поля в текущей таблице, которое будет ключом
			'project', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'CASCADE'
		);

		$this->createTable('task_responsible', [
			'id' => $this->primaryKey(),
			'project_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
		]);
		$this->addForeignKey(
			'task_responsible_project_id',  // это "условное имя" ключа
			'task_responsible', // это название текущей таблицы
			'project_id', // это имя поля в текущей таблице, которое будет ключом
			'project', // это имя таблицы, с которой хотим связаться
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
			'task_status_project_id',
			'task_status'
		);
		$this->dropTable('task_status');

		$this->dropForeignKey(
			'task_type_project_id',
			'task_type'
		);
		$this->dropTable('task_type');

		$this->dropForeignKey(
			'task_responsible_project_id',
			'task_responsible'
		);
		$this->dropTable('task_responsible');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211209_130749_create_tables_type_status_responsible cannot be reverted.\n";

        return false;
    }
    */
}
