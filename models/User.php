<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
  /*  public $id;
    public $name;
    public $email;
    public $password_hash;
	public $status;
	public $auth_key;*/


	/**
	 * defining table name to work with
	 *
	 * @return string
	 */
	public static function tableName()
	{
		return 'users';
	}

    /**
     * Will find identity by primary key
     *
     * @param   int $id
     * @inheritdoc
     */
	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

    /**
     * not implementing this method, cause we don't need it in this simple app
     *
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
	    throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by usernames(in this simple app username = email)
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
	    return static::findOne(['email' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * Set password hash using safe method provided by framework
	 *
	 * @param $password
	 */
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current record
     */
    public function validatePassword($password)
    {
	    return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * Validate authKey token against db record
	 *
	 * @param string $authKey
	 * @return bool
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}


	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}
}
