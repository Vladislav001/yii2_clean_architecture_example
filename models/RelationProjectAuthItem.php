<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "relation_project_auth_item".
 *
 * @property int $project_id
 * @property string $name
 * @property int $user_id
 *
 * @property Project $project
 * @property User $user
 */
class RelationProjectAuthItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relation_project_auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'name', 'user_id'], 'required'],
            [['project_id', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['project_id', 'name', 'user_id'], 'unique', 'targetAttribute' => ['project_id', 'name', 'user_id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'name' => 'Name',
            'user_id' => 'User ID',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
