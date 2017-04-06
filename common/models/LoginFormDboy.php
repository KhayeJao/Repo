<?php

namespace common\models;

use Yii;
use yii\base\Model;
 
/**
 * Login form
 */
class LoginFormDboy extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            ['username', 'required','message' => 'Username or Mobile cannot be blank'],
            ['password', 'required'],
            
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
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
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() {
		
        if ($this->_user === false) {
			 $model = new \common\models\DeliveryBoy();
			  
            $this->_user = $model::findByUsername($this->username);
                
            if (!$this->_user) { /* IF ADDED BY ANISH M TO LOGIN VIA MOBILE NO */
                $this->_user = $model::findByMobileno($this->username);
            }
            if (!$this->_user) { /* IF ADDED BY ANISH M TO LOGIN VIA EMAIL */
                $this->_user = $model::findByEmail($this->username);
            }
        }

        return $this->_user;
    }

}
