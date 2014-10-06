<?php
/**
 * Created by PhpStorm.
 * User: cita
 * Date: 10/3/14
 * Time: 1:01 AM
 */

namespace app\models;

use yii\db\ActiveRecord;
use yii\base\Exception;
use yii\base\Model;


class Message extends ActiveRecord{
	/**
	 * defining table name to work with
	 *
	 * @return string
	 */
	public static function tableName()
	{
		return 'messages';
	}

	public static function sendMessage(\app\models\User $author,\app\models\User  $recipient, $message) {
		$newMessage = new Message();
		$newMessage->author_id = $author->getId();
		$newMessage->recipient_id = $recipient->getId();
		$newMessage->message = $message;
		$newMessage->date_posted = date('Y-m-d H:i:s');
		return $newMessage->save();
	}

	public function getChatFor(\app\models\User $author, \app\models\User $recipient) {
		$whereArgs = [$author->getId(), $recipient->getId()];


		return Message::find()->where('author_id = :author AND recipient_id = :recipient', ['author' => $author->getId(), 'recipient' => $recipient->getId()])
			->orWhere('recipient_id = :author AND author_id = :recipient', ['author' => $author->getId(), 'recipient' => $recipient->getId()]);

	}

	public function getAuthor() {
		return $this->hasOne(\app\models\User::className(), ['id' => 'author_id']);
	}

	public function getRecipient() {
		return $this->hasOne(\app\models\User::className(), ['id' => 'recipient_id']);
	}
} 