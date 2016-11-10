<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property integer $status_id
 * @property integer $permission_id
 *
 * @property Comment[] $comments
 * @property Post[] $posts
 */
class User extends ActiveRecord
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['!name', '!password'], 'required', 'on' => self::SCENARIO_LOGIN],

            [['status_id', 'permission_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['password', 'email'], 'string', 'max' => 255],
            ['email', 'email']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'password' => 'Password',
            'email' => 'Email',
            'status_id' => 'Status ID',
            'permission_id' => 'Permission ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['user_id' => 'id']);
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['username', 'password', '!status_id', '!permission_id'],
            self::SCENARIO_REGISTER => ['username', 'email', 'password', 'status_id', 'permission_id'],
        ];
    }

    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password']);
        return $fields;
    }
}
