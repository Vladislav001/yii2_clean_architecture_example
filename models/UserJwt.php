<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_jwt".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $access_token
 * @property int|null $access_token_expire_at
 * @property string|null $refresh_token
 * @property int|null $refresh_token_expire_at
 *
 * @property User $user
 */
class UserJwt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_jwt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'access_token_expire_at', 'refresh_token_expire_at'], 'integer'],
            [['access_token', 'refresh_token'], 'string', 'max' => 10000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'access_token' => 'Access Token',
            'access_token_expire_at' => 'Access Token Expire At',
            'refresh_token' => 'Refresh Token',
            'refresh_token_expire_at' => 'Refresh Token Expire At',
        ];
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
