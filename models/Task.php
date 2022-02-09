<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $direction_id
 * @property string $name
 * @property int $type
 * @property string|null $term_plan
 * @property int $status
 * @property int $responsible
 * @property string|null $link
 * @property string|null $link_result
 * @property string|null $comment
 * @property int $sort
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Direction $direction
 * @property TaskResponsible $responsible0
 * @property TaskStatus $status0
 * @property TaskType $type0
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['direction_id', 'name', 'type', 'status', 'responsible'], 'required'],
            [['direction_id', 'type', 'status', 'responsible', 'sort'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'term_plan', 'link', 'link_result'], 'string', 'max' => 255],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::className(), 'targetAttribute' => ['direction_id' => 'id']],
            [['responsible'], 'exist', 'skipOnError' => true, 'targetClass' => TaskResponsible::className(), 'targetAttribute' => ['responsible' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => TaskType::className(), 'targetAttribute' => ['type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'direction_id' => 'Direction ID',
            'name' => 'Name',
            'type' => 'Type',
            'term_plan' => 'Term Plan',
            'status' => 'Status',
            'responsible' => 'Responsible',
            'link' => 'Link',
            'link_result' => 'Link Result',
            'comment' => 'Comment',
            'sort' => 'Sort',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Direction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirection()
    {
        return $this->hasOne(Direction::className(), ['id' => 'direction_id']);
    }

    /**
     * Gets query for [[Responsible0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsible0()
    {
        return $this->hasOne(TaskResponsible::className(), ['id' => 'responsible']);
    }

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(TaskStatus::className(), ['id' => 'status']);
    }

    /**
     * Gets query for [[Type0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(TaskType::className(), ['id' => 'type']);
    }
}
