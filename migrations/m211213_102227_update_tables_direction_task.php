<?php

use yii\db\Migration;

/**
 * Class m211213_102227_update_tables_direction_task
 */
class m211213_102227_update_tables_direction_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('direction','is_archive', $this->integer()->after('name'));

		$this->alterColumn('task','type', $this->integer()->notNull());
		$this->addForeignKey(
			'task_task_type_id',  // это "условное имя" ключа
			'task', // это название текущей таблицы
			'type', // это имя поля в текущей таблице, которое будет ключом
			'task_type', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'RESTRICT'
		);

		$this->alterColumn('task','status', $this->integer()->notNull());
		$this->addForeignKey(
			'task_task_status_id',  // это "условное имя" ключа
			'task', // это название текущей таблицы
			'status', // это имя поля в текущей таблице, которое будет ключом
			'task_status', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'RESTRICT'
		);

		$this->alterColumn('task','responsible', $this->integer()->notNull());
		$this->addForeignKey(
			'task_task_responsible_id',  // это "условное имя" ключа
			'task', // это название текущей таблицы
			'responsible', // это имя поля в текущей таблице, которое будет ключом
			'task_responsible', // это имя таблицы, с которой хотим связаться
			'id', // это поле таблицы, с которым хотим связаться
			'RESTRICT'
		);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropColumn('direction', 'is_archive');

		$this->dropForeignKey(
			'task_task_type_id',
			'task'
		);

		$this->dropForeignKey(
			'task_task_status_id',
			'task'
		);

		$this->dropForeignKey(
			'task_task_responsible_id',
			'task'
		);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211213_102227_update_tables_direction_task cannot be reverted.\n";

        return false;
    }
    */
}
