<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * RegistrationForm is the model behind the Registration form.
 */
class ProfileForm extends Model
{
    public $name;
    public $email;
    public $password;
	public $passwordVerify;
	public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // only status change is allowed
            [['status'], 'required'],
        ];
    }

    /**
     * Create new record in db for user by values provided with this model.
     * @return boolean whether the model passes validation
     */
    public function update()
    {
        if ($this->validate()) {
			$user = Yii::$app->user->identity;
	        $user->status = $this->status;


            return $user->save();;
        } else {
            return false;
        }
    }
}
