<?php

namespace api\modules\v1\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use api\modules\v1\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use yii\web\Response;
use yii\helpers\Json;
use common\components\Sms;
use api\modules\v1\models\User;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller {

    public $modelClass = 'common\models\LoginForm';
    private $response = array(
        'status' => 0,
        'message' => 0,
    );

    public function init() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
        header("Access-Control-Allow-Headers: x-requested-with");
        parent::init();
        Yii::$app->user->enableSession = FALSE;
        if (Yii::$app->request->post('app_token') != Yii::$app->params['application_token']) {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid token';
            echo Json::encode($this->response);
            exit;
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {

        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }

//    public function beforeAction($action) {
//        parent::beforeAction($action);
//
////        if (Yii::$app->request->post('app_token') != Yii::$app->params['application_token']) {
////            $this->response['status'] = 0;
////            $this->response['message'] = 'Invalid token';
////            echo \yii\helpers\Json::encode($this->response);
////        }
//    }

    public function actionLogin() {

        $model = new LoginForm();
        $model->username = Yii::$app->request->post('username');
        $model->password = Yii::$app->request->post('password');
		  print_r($_REQUEST);die;
        if ($model->login()) {
            $this->response['status'] = 1;
            $this->response['message'] = 'User Information';
            $user_info = \common\models\base\User::findOne(['id' => $model->getUser()->id]);
            $this->response['info'] = array(
                'id' => $user_info->id,
                'username' => $user_info->username,
                'email' => $user_info->email,
                'first_name' => $user_info->first_name,
                'last_name' => $user_info->last_name,
                'mobile_no' => $user_info->mobile_no,
                'fb_id' => $user_info->fb_id,
                'fb_profile' => $user_info->fb_profile,
                'is_mobile_verified' => $user_info->is_mobile_verified,
                'is_email_verified' => $user_info->is_email_verified,
                'reward_points' => $user_info->reward_points);
        } else {
            $error_arr = $model->errors;
            reset($error_arr);
            $first_key = key($error_arr);
            $this->response['status'] = 0;
            $this->response['message'] = $model->errors[$first_key];
        }
        return $this->response;
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

    private function getRandomPassword() {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 8; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $res;
    }

    public function actionFblogin() {
        $fb_id = Yii::$app->request->post('fb_id');
        $user_info = User::findOne(['fb_id' => $fb_id]);
        if ($user_info) {
            $this->response['status'] = 1;
            $this->response['message'] = 'User Information';
            $this->response['info'] = array(
                'id' => $user_info->id,
                'username' => $user_info->username,
                'email' => $user_info->email,
                'first_name' => $user_info->first_name,
                'last_name' => $user_info->last_name,
                'mobile_no' => $user_info->mobile_no,
                'fb_id' => $user_info->fb_id,
                'fb_profile' => $user_info->fb_profile,
                'is_mobile_verified' => $user_info->is_mobile_verified,
                'is_email_verified' => $user_info->is_email_verified);
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'Facebook user not registered';
        }
        return $this->response;
    }

    public function actionSignup() {
        $isSocial = Yii::$app->request->post()["SignupForm"]["isSocial"];
        if ($isSocial == 'false') {
            $model = new SignupForm();
            if ($model->load(Yii::$app->request->post())) {
                //$model->fb_id = "";
                $model->fb_profile = "";
                $model->mobile_v_code = '' . rand(999, 9999) . '';
                $model->is_mobile_verified = "No";
                $model->is_email_verified = "No";
                $model->type = "customer";
                $model->last_login_ip = Yii::$app->getRequest()->getUserIP();

                if ($user = $model->signup()) {

                    if (Yii::$app->getUser()->login($user)) {
                        $sms = new Sms();
                        $message = "Your mobile verification code for KhayeJao is " . $user->mobile_v_code;
                        $sms->send($user->mobile_no, $message);
                        $this->response['status'] = 1;
                        $this->response['message'] = "Profile created successfully";
                        $user_info = \common\models\base\User::findOne(['id' => $user->id]);
                        $this->response['info'] = array(
                            'id' => $user_info->id,
                            'username' => $user_info->username,
                            'email' => $user_info->email,
                            'first_name' => $user_info->first_name,
                            'last_name' => $user_info->last_name,
                            'mobile_no' => $user_info->mobile_no,
                            'fb_id' => $user_info->fb_id,
                            'fb_profile' => $user_info->fb_profile,
                            'is_mobile_verified' => $user_info->is_mobile_verified,
                            'is_email_verified' => $user_info->is_email_verified);
                    }
                } else {
                    $this->response['status'] = 0;
                    $this->response['message'] = "Error!!!";
                    $this->response['errors'] = $model->getErrors();
                }
                return $this->response;
            }
        } else {
            $fb_id = Yii::$app->request->post()["SignupForm"]["fb_id"];
            $user = User::find()->where(['fb_id' => $fb_id])->count();
            $customer = User::find()
                    ->where(['fb_id' => $fb_id])
                    ->one();
            if ($user == 0) {
                $model = new SignupForm();
                if ($model->load(Yii::$app->request->post())) {
                    $user_email = explode("@", $model->email);
                    $model->username = $user_email[0];
                    $model->password = $this->getRandomPassword();
                    $model->fb_profile = $user_email[0];
                    $model->mobile_v_code = '' . rand(999, 9999) . '';
                    $model->is_mobile_verified = "No";
                    $model->is_email_verified = "No";
                    $model->type = "customer";
                    $model->last_login_ip = Yii::$app->getRequest()->getUserIP();
                    if ($user = $model->signup()) {
                        if (Yii::$app->getUser()->login($user)) {
                            $user_info = \common\models\base\User::findOne(['id' => $user->id]);
                            $this->response['info'] = array(
                                'id' => $user_info->id,
                                'username' => $user_info->username,
                                'email' => $user_info->email,
                                'first_name' => $user_info->first_name,
                                'last_name' => $user_info->last_name,
                                'mobile_no' => $user_info->mobile_no,
                                'fb_id' => $user_info->fb_id,
                                'fb_profile' => $user_info->fb_profile,
                                'is_mobile_verified' => $user_info->is_mobile_verified,
                                'is_email_verified' => $user_info->is_email_verified);
                            $sms = new Sms();
                            $message = "Your mobile verification code for KhayeJao is " . $user->mobile_v_code;
                            $sms->send($user->mobile_no, $message);
                            $this->response['status'] = 1;
                            $this->response['message'] = "Profile created successfully";
                        } else {
                            $error_arr = $model->errors;
                            reset($error_arr);
                            $first_key = key($error_arr);
                            $this->response['status'] = 0;
                            $this->response['message'] = $model->errors[$first_key];
                        }
                    } else {
                        $this->response['status'] = 0;
                        $this->response['message'] = "Error!!!";
                        $this->response['errors'] = $model->getErrors();
                    }
                    return $this->response;
                }
            } else {
                $model = User::findOne($customer->id);
                $this->response['status'] = 1;
                $this->response['message'] = 'User Information';
                $user_info = \common\models\base\User::findOne(['id' => $model->id]);
                $this->response['info'] = array(
                    'id' => $user_info->id,
                    'username' => $user_info->username,
                    'email' => $user_info->email,
                    'first_name' => $user_info->first_name,
                    'last_name' => $user_info->last_name,
                    'mobile_no' => $user_info->mobile_no,
                    'fb_id' => $user_info->fb_id,
                    'fb_profile' => $user_info->fb_profile,
                    'is_mobile_verified' => $user_info->is_mobile_verified,
                    'is_email_verified' => $user_info->is_email_verified);
                return $this->response;
            }
        }
    }

    public function actionCheckmobile() {
        $fb_id = Yii::$app->request->post("fb_id");
        $user = User::find()->where(['fb_id' => $fb_id])->count();
        if ($user == 0) {
            $this->response['status'] = 0;
        } else {
            $this->response['status'] = 1;
        }
        return $this->response;
    }

    /* CUSTOM FUNNCTION BY ANISH M -STARTS */

    public function actionEditprofile() {
        $id = Yii::$app->request->post()["User"]["id"];
        $model = new User();
        $user = User::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->id = $id;
            if ($model->validate()) {
                $user = $model->edit($id, $model);
                if ($user) {
                    $this->response['status'] = 1;
                    $this->response['message'] = "Profile updated successfully";
                    $user_info = \common\models\base\User::findOne(['id' => $id]);
                    $this->response['data'] = array(
                        'id' => $user_info->id,
                        'username' => $user_info->username,
                        'email' => $user_info->email,
                        'first_name' => $user_info->first_name,
                        'last_name' => $user_info->last_name,
                        'mobile_no' => $user_info->mobile_no,
                        'fb_id' => $user_info->fb_id,
                        'fb_profile' => $user_info->fb_profile,
                        'is_mobile_verified' => $user_info->is_mobile_verified,
                        'is_email_verified' => $user_info->is_email_verified);
                } else {
                    $this->response['status'] = 0;
                    $this->response['message'] = "Could not update profile";
                    $this->response['errors'] = $model->getErrors();
                }
            } else {
                $this->response['status'] = 0;
                $this->response['message'] = "Error!!!";
                $this->response['errors'] = $model->getErrors();
            }
        }

        return $this->response;
    }

    /* CUSTOM FUNNCTION BY ANISH M -ENDS */

    public function actionRequestpasswordreset() {

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->response['status'] = 1;
                $this->response['message'] = "Check your email for further instructions.";
            } else {
                $this->response['status'] = 0;
                $this->response['message'] = "Sorry, we are unable to reset password for email provided."