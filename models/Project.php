<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string|null $logo
 * @property int $sort
 *
 * @property User $creator
 * @property Direction[] $directions
 * @property RelationProjectAuthItem[] $relationProjectAuthItems
 * @property TaskResponsible[] $taskResponsibles
 * @property TaskStatus[] $taskStatuses
 * @property TaskType[] $taskTypes
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['creator_id', 'name'], 'required'],
            [['creator_id', 'sort'], 'integer'],
            [['name', 'logo'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator_id' => 'Creator ID',
            'name' => 'Name',
            'logo' => 'Logo',
            'sort' => 'Sort',
        ];
    }

    /**
     * Gets query for [[Creator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * Gets query for [[Directions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirections()
    {
        return $this->hasMany(Direction::className(), ['project_id' => 'id']);
    }

    /**
     * Gets query for [[RelationProjectAuthItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelationProjectAuthItems()
    {
        return $this->hasMany(RelationProjectAuthItem::className(), ['project_id' => 'id']);
    }

    /**
     * Gets query for [[TaskResponsibles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskResponsibles()
    {
        return $this->hasMany(TaskResponsible::className(), ['project_id' => 'id']);
    }

    /**
     * Gets query for [[TaskStatuses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskStatuses()
    {
        return $this->hasMany(TaskStatus::className(), ['project_id' => 'id']);
    }

    /**
     * Gets query for [[TaskTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskTypes()
    {
        return $this->hasMany(TaskType::className(), ['project_id' => 'id']);
    }
}
