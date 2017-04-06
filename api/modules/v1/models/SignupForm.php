<?php

namespace api\modules\v1\models;

use common\models\User as User1;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $mobile_no;
    public $fb_id;
    public $fb_profile;
    public $mobile_v_code;
    public $is_mobile_verified;
    public $is_email_verified;
    public $type;
    public $last_login_ip;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Your username can only contain alphanumeric characters, underscores and dashes.'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['first_name', 'last_name', 'mobile_no'], 'required'],
            [['is_mobile_verified', 'is_email_verified', 'type'], 'required'],
            [['is_mobile_verified', 'is_email_verified', 'type'], 'string'],
            [['fb_id', 'fb_profile'], 'string', 'max' => 255],
            ['mobile_no', 'validateMobile'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['mobile_no'], 'string', 'max' => 15],
            [['mobile_v_code'], 'string', 'max' => 10],
            [['last_login_ip'], 'string', 'max' => 20]
        ];
    }

    public function validateMobile($attribute, $params) {

        if ($this->mobile_no) {
            $user_found = User::findOne(['mobile_no' => $this->mobile_no]);
            if ($user_found) {
                $this->addError($attribute, 'Mobile No has alreary been registered with us');
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if ($this->validate()) {
            $user = new User1();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->mobile_no = $this->mobile_no;
            $user->fb_id = $this->fb_id;
            $user->fb_profile = $this->fb_profile;
            $user->mobile_v_code = $this->mobile_v_code;
            $user->is_mobile_verified = $this->is_mobile_verified;
            $user->is_email_verified = $this->is_email_verified;
            $user->type = $this->type;
            $user->last_login_ip = $this->last_login_ip;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

}
