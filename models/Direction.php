<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "direction".
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 *
 * @property Project $project
 * @property Task[] $tasks
 */
class Direction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'name'], 'required'],
            [['project_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
			[['project_id', 'name'], 'unique', 'targetAttribute' => ['project_id', 'name']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Project]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['direction_id' => 'id']);
    }
}
