<?php

use yii\db\Migration;

/**
 * Class m211109_061339_create_table_task
 */
class m211109_061339_create_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('task', [
			'id' => $this->primaryKey(),
			'direction_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
			'type' => $this->integer(),
			'term_plan' => $this->string(),
			'status' => $this->integer(),
			'responsible' => $this->string(),
			'link_result' => $this->string(),
			'comment' => $this->text(),
		]);

		$this->addForeignKey(
			'direction_id',  // это "условное имя" ключа
			'task', // это название текущей таблицы
			'direction_id', // это имя поля в текущей таблице, которое будет ключом
			'direction', // это имя таблицы, с которой хотим связаться
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
			'project_id',
			'task'
		);

		$this->dropTable('task');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211109_061339_create_table_task cannot be reverted.\n";

        return false;
    }
    */
}
