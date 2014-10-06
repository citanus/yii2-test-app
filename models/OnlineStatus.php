<?php
/**
 * Created by PhpStorm.
 * User: cita
 * Date: 10/3/14
 * Time: 1:01 AM
 */

namespace app\models;

use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class OnlineStatus
 *
 * If there is record for user_id in online status table, then we can assume user is online
 *
 * @todo refactor to use some memory based storage, cause often updates of online status will generate many write
 * operations
 *
 * @package app\models
 */
class OnlineStatus extends ActiveRecord {

	/**
	 * defining table name to work with
	 *
	 * @return string
	 */
	public static function tableName()
	{
		return 'users_online';
	}

	/**
	 * Update last online datetime for given UserId and clean up zombie records for other users
	 *
	 * @param User $currentUser
	 * @return bool whether the saving succeeds
	 */
	public static function updateStatus(User $currentUser) {
		$currentUserStatus = OnlineStatus::findOne(['user_id' => $currentUser->getId()]);
		if ($currentUserStatus === null) {
			$currentUserStatus = new OnlineStatus();
			$currentUserStatus->user_id = $currentUser->getId();
		}
		$currentUserStatus->date_last_online = date('Y-m-d H:i:s');
		$updateStatus = $currentUserStatus->save();

		// delete all records older then one minute
		OnlineStatus::deleteAll(sprintf("date_last_online < '%s'", date('Y-m-d H:i:s', time()-60)));
		return $updateStatus;
	}
} 