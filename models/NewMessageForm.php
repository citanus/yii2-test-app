<?php
/**
 * Created by PhpStorm.
 * User: cita
 * Date: 10/6/14
 * Time: 3:22 PM
 */

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

class NewMessageForm extends Model{
	public $message;
	public $recipient;

	/**
	 * @todo validate if recipient is in users contactlist
	 * @return array
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['message', 'recipient'], 'required'],
		];
	}
} 