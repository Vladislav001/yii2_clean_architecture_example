<?php

use yii\db\Migration;

/**
 * Class m211109_060422_create_table_project
 */
class m211109_060422_create_table_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('project', [
			'id' => $this->primaryKey(),
			'creator_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
			'logo' => $this->string(),
		]);

		$this->addForeignKey(
			'creator_id',  // это "условное имя" ключа
			'project', // это название текущей таблицы
			'creator_id', // это имя поля в текущей таблице, которое будет ключом
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
			'creator_id',
			'project'
		);

		$this->dropTable('project');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211109_060422_create_table_project cannot be reverted.\n";

        return false;
    }
    */
}
