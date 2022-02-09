<?php

use yii\db\Migration;

/**
 * Class m211209_125054_update_tables_direction_task
 */
class m211209_125054_update_tables_direction_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('direction','created_at', $this->dateTime()->notNull()->defaultValue(new \yii\db\Expression('NOW()')));
		$this->addColumn('direction','updated_at', $this->dateTime());
		$this->addColumn('task','created_at', $this->dateTime()->notNull()->defaultValue(new \yii\db\Expression('NOW()')));
		$this->addColumn('task','updated_at', $this->dateTime());
		$this->addColumn('task','link', $this->string()->after('responsible'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('direction', 'created_at');
		$this->dropColumn('direction', 'updated_at');
		$this->dropColumn('task', 'created_at');
		$this->dropColumn('task', 'updated_at');
		$this->dropColumn('task', 'link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211209_125054_update_tables_direction_task cannot be reverted.\n";

        return false;
    }
    */
}
