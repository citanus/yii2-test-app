<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * RegistrationForm is the model behind the Registration form.
 */
class RegistrationForm extends Model
{
    public $name;
    public $email;
    public $password;
	public $passwordVerify;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'password', 'passwordVerify'], 'required'],

	        // check if username isn't already taken
	        ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],

            // email has to be a valid email address
            ['passwordVerify', 'compare','compareAttribute' => 'password', 'message'=>'Must be same as password above'],
        ];
    }

    /**
     * Create new record in db for user by values provided with this model.
     * @return boolean whether the model passes validation
     */
    public function register()
    {
        if ($this->validate()) {
			$user = new User();
	        $user->name = $this->name;
	        $user->email = $this->email;
	        $user->generateAuthKey(); // @todo should be moved to some onSave event...
	        $user->setPassword($this->password);
	        $user->status = 'just registered';
            return $user->save();;
        } else {
            return false;
        }
    }
}
