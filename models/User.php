<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Project[] $projects
 * @property RelationProjectAuthItem[] $relationProjectAuthItems
 * @property UserJwt[] $userJwts
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'password'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'name', 'password'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'password' => 'Password',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Projects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['creator_id' => 'id']);
    }

    /**
     * Gets query for [[RelationProjectAuthItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelationProjectAuthItems()
    {
        return $this->hasMany(RelationProjectAuthItem::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserJwts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserJwts()
    {
        return $this->hasMany(UserJwt::className(), ['user_id' => 'id']);
    }
}
