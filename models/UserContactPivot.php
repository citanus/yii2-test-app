<?php
/**
 * Created by PhpStorm.
 * User: cita
 * Date: 10/6/14
 * Time: 12:05 PM
 */

namespace app\models;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

/**
 * Class UserContactPivot
 *
 * Represents pivot table
 *
 * @package app\models
 */
class UserContactPivot extends ActiveRecord {
	/**
	 * defining table name to work with
	 *
	 * @return string
	 */
	public static function tableName()
	{
		return 'user_contact_list';
	}


} 