<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use common\models\User;
use Yii;

/**
 * CampaignController implements the CRUD actions for Area model.
 */
class CampaignController extends Controller {

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'previewmail'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }

    public function actionCreate() {
        $users = new User();
        if ($_POST) {
            $content = trim($_POST['Content']['content']);
            $sub = $_POST['subject'];
            foreach ($_POST['User']['id'] as $key => $id) {
                $user = $users->findOne(['id' => $id]);
                Yii::$app->mailer->compose('campaign/campaign', ['content' => $content])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ''])
                        ->setTo($user->email)
                        ->setSubject($sub)
                        ->send();
            }
        }
        return $this->render('create', [
                    'users' => $users
        ]);
    }

    public function actionPreviewmail() {
        if ($_POST) {
            $content = trim($_POST['Content']);
            $Subject = trim($_POST['Subject']);
            $email = Yii::$app->params['adminEmail'];
            $mail = \Yii::$app->mailer->compose('campaign/campaign', ['content' => $content])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ''])
                    ->setTo($email)
                    ->setSubject($Subject)
                    ->send();
            if ($mail):
                echo "Campaign preview mail send to " . $email . " email address.";
                return;
            endif;
        }
    }

}
