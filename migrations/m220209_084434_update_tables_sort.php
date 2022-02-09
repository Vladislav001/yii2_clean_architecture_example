<?php

use yii\db\Query;
use yii\db\Migration;

/**
 * Class m220209_084434_update_tables_sort
 */
class m220209_084434_update_tables_sort extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	// direction
		$this->addColumn('direction','sort', $this->integer()->notNull()->defaultValue(0)->after('is_archive'));
		foreach((new Query)->from('direction')->each() as $direction) {
			$this->update('direction', ['sort' => $direction['number']], ['id' => $direction['id']]);
		}
		$this->dropColumn('direction','number');

		// task
		$this->addColumn('task','sort', $this->integer()->notNull()->defaultValue(0)->after('comment'));
		foreach((new Query)->from('task')->each() as $task) {
			$this->update('task', ['sort' => $task['number']], ['id' => $task['id']]);
		}
		$this->dropColumn('task','number');

		// project
		$this->addColumn('project','sort', $this->integer()->notNull()->defaultValue(0));

		// task_responsible
		$this->addColumn('task_responsible','sort', $this->integer()->notNull()->defaultValue(0));

		// task_status
		$this->addColumn('task_status','sort', $this->integer()->notNull()->defaultValue(0));

		// task_type
		$this->addColumn('task_type','sort', $this->integer()->notNull()->defaultValue(0));
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		// direction
		$this->addColumn('direction','number', $this->integer()->after('is_archive'));
		foreach((new Query)->from('direction')->each() as $direction) {
			$this->update('direction', ['number' => $direction['sort']], ['id' => $direction['id']]);
		}
		$this->dropColumn('direction', 'sort');

		// task
		$this->addColumn('task','number', $this->integer()->after('comment'));
		foreach((new Query)->from('task')->each() as $task) {
			$this->update('task', ['number' => $task['sort']], ['id' => $task['id']]);
		}
		$this->dropColumn('task','sort');

		// project
		$this->dropColumn('project','sort');

		// task_responsible
		$this->dropColumn('task_responsible','sort');

		// task_status
		$this->dropColumn('task_status','sort');

		// task_type
		$this->dropColumn('task_type','sort');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220209_084434_update_tables_sort cannot be reverted.\n";

        return false;
    }
    */
}
