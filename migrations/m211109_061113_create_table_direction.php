<?php

use yii\db\Migration;

/**
 * Class m211109_061113_create_table_direction
 */
class m211109_061113_create_table_direction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('direction', [
			'id' => $this->primaryKey(),
			'project_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
		]);

		$this->addForeignKey(
			'project_id',  // это "условное имя" ключа
			'direction', // это название текущей таблицы
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
			'project_id',
			'direction'
		);

		$this->dropTable('direction');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211109_061113_create_table_direction cannot be reverted.\n";

        return false;
    }
    */
}
