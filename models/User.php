<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class User
 *
 * @package app\models
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
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
     * Validates password against db record
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

	/**
	 * If there is record in online status table, then we assume that user is online
	 *
	 * @todo relocate this method to separate object
	 * @todo refactor to use some memory based storage, cause often updates of online status will generate many write
	 * operations
	 *
	 * @return bool
	 */
	public function isOnline() {
		return OnlineStatus::findOne(['user_id' => $this->getId(), ]) !== null;
	}


	/**
	 * helper method for getContacts()
	 *
	 * @return ActiveQuery
	 */
	public function getUserContacts(){
		return $this->hasMany(UserContactPivot::className(), ['user_id' => 'id']);

	}

	/**
	 * users in contact list via UserContactPivot
	 *
	 * @return ActiveQuery
	 */
	public function getContacts() {
		return $this->hasMany(User::className(), ['id'=>'contact_id'])->via('userContacts');
	}

	/**
	 * add $user to user_contact_list pivot table
	 *
	 * @todo use REPLACE insteadof SELECT + INSERT. Or extend ActiveRecord with ?findOneOrNew([pk = ?])
	 * @param User $contact
	 * @return bool
	 */
	public function addUserToContactList(\app\models\User $contact) {
		$userContactPivot = UserContactPivot::findOne(['user_id' => $this->getId(), 'contact_id' => $contact->getId()]);
		if ($userContactPivot === null) {
			$userContactPivot = new UserContactPivot();
			$userContactPivot->user_id = $this->getId();
			$userContactPivot->contact_id = $contact->getId();
			return $userContactPivot->save();
		}
		return false;
	}

	/**
	 * not implementing this method, cause we don't need it in this simple app
	 *
	 * @param User $contact
	 * @throws \yii\base\NotSupportedException
	 */
	public function removeUserFromContactList(\app\models\User $contact) {
		throw new NotSupportedException('"removeUser" is not implemented.');
	}


	/**
	 * wrappwer method to Message::loadChatFor()
	 *
	 * @return ActiveQuery
	 */
	public function getChatWithContact(\app\models\User $recipient) {
		return Message::getChatFor($this, $recipient);
	}
}