<?php

namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_SOFT_REG = 'comment';
    const SOFT_REGISTERED = 0;
    const HARD_REGISTERED = 1;

    private $_user;

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
            [['name', 'email'], 'required', 'on' => self::SCENARIO_SOFT_REG],
            [['status_id', 'permission_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['password', 'email'], 'string', 'max' => 255, 'min' => 6],
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
            self::SCENARIO_LOGIN => ['name', 'password', '!status_id', '!permission_id'],
            self::SCENARIO_REGISTER => ['name', 'email', 'password', 'auth_token', 'status_id', 'permission_id'],
            self::SCENARIO_SOFT_REG => ['name', 'email', 'status_id'],
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

    public function register()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User(['scenario' => self::SCENARIO_REGISTER]);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status_id = self::HARD_REGISTERED;
        return $user->save() ? $user : null;
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status_id' => self::HARD_REGISTERED]);
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser()/*, $this->rememberMe ? 3600 * 24 * 30 : 0*/);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->name);
        }
        return $this->_user;
    }

    /**
     * @param $name
     * @return static
     */
    public static function findByUsername($name)
    {
        return static::findOne(['name' => $name, 'status_id' => self::HARD_REGISTERED]);
    }

    /**
     * @return User|null
     */
    public function softreg()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User(['scenario' => self::SCENARIO_SOFT_REG]);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->status_id = self::SOFT_REGISTERED;
        return $user->save() ? $user : null;
    }
}
