<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $post
 * @property string $created_at
 *
 * @property Comment[] $comments
 * @property User $user
 */
class Post extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'post', 'title'], 'required'],
            [['user_id'], 'integer'],
            [['post'], 'string'],
            [['title'], 'string'],
            [['created_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'post' => 'Post',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function gainLasts($limit = 5)
    {
        $limit = is_int($limit) ? $limit : 5;
        return self::find()->joinWith('user')->orderBy(['posts.created_at' => SORT_DESC])->limit($limit)->all();
    }

    /**
     * @return $this
     */
    public static function findWithUser()
    {
        return self::find()->joinWith('user');
    }

    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        return $this->save() ? $this : null;
    }
}
