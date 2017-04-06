<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;

function getRandomCoupinCode() {
    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $res = "";
    for ($i = 0; $i < 8; $i++) {
        $res .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $res;
}

/**
 * @var yii\web\View $this
 * @var common\models\Coupons $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupons-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Coupons',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>
        <?php
//        Modal::begin([
//            'header' => '<h2>Select User</h2>',
//            'toggleButton' => ['label' => 'Select User', 'class' => 'btn btn-normal', 'style' => 'display : none;'],
//        ]);
//
//
//        echo Select2::widget([
//            'name' => 'user_id_dropdown',
//            'id' => 'user_id_dropdown',
//            'data' => ArrayHelper::map(\common\models\base\User::findAll(['type' => 'customer']), 'id', 'username'),
//            'options' => [
//                'placeholder' => 'Select user ...',
//            ],
//        ]);
//
//        Modal::end();
//
        ?>

        <p>
            <?php ?>
            <?= $form->field($model, 'code')->textInput(['maxlength' => 30, 'value' => ($model->isNewRecord ? getRandomCoupinCode() : $model->code)]) ?>
            <?php
            $dropdown_options = array();
            if (!$model->isNewRecord) {
                $dropdown_options['disabled'] = 'disabled';
            }
            if (\Yii::$app->user->can('manageRestaurantCoupons')) {
                echo $form->field($model, 'coupon_key')->dropDownList(array_merge(["" => ""], ArrayHelper::map(\common\models\base\DiscountKey::findAll(['type' => 'Restaurant']), 'key_type', 'key_type')), $dropdown_options);
                $this->registerJs("var coupon_key_arr_str = '" . implode("^_^", ArrayHelper::map(\common\models\base\DiscountKey::findAll(['type' => 'Restaurant']), 'key_type', 'type')) . "';", \yii\web\VIEW::POS_END);
            } else {
                echo $form->field($model, 'coupon_key')->dropDownList(array_merge(["" => ""], ArrayHelper::map(\common\models\base\DiscountKey::find()->where(['type' => 'Restaurant'])->orWhere(['type' => 'Open'])->all(), 'key_type', 'key_type')), $dropdown_options);
                $this->registerJs("var coupon_key_arr_str = '" . implode("^_^", ArrayHelper::map(\common\models\base\DiscountKey::find()->where(['type' => 'Restaurant'])->orWhere(['type' => 'Open'])->all(), 'key_type', 'type')) . "';", \yii\web\VIEW::POS_END);
            }
            ?>


        <div id="perameters_div" class="form-group field-coupons-type required" style="display: none;">
            <label class="control-label col-sm-3" for="coupons-type">Perameters</label>
            <div class="col-sm-6" id="perameters_content_div">

            </div>

        </div>
        <?= $form->field($model, 'title')->textInput(['maxlength' => 200]) ?>
        <?= $form->field($model, 'description')->textInput(['maxlength' => 500]) ?>
        <?php //$form->field($model, 'coupon_perameter')->textInput(['maxlength' => 700]) ?>
        <?= $form->field($model, 'notify')->dropDownList([ 'No' => 'No', 'Yes' => 'Yes',], ['prompt' => '']) ?>
         <?= $form->field($model, 'deal_mobile_app_only')->dropDownList([ 'No' => 'No', 'Yes' => 'Yes',], ['prompt' => '']) ?>
        <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => '']) ?>
        <?php
//            echo $form->field($model, 'type')->dropDownList([ 'Restaurant' => 'Restaurant', 'Open' => 'Open',], ['prompt' => '']);

        echo Html::hiddenInput('Coupons[type]', '', ['id' => 'coupons-type']);
//            echo $form->field($model, 'type')->hiddenInput(['value' => 'Restaurant'])->label(FALSE);
        ?>
        <?php
        if (\Yii::$app->user->can('manageCoupons')) {
            $restaurant_array = ArrayHelper::map(\common\models\base\Restaurant::find()->all(), 'id', 'title');
        } else {
            $restaurant_array = ArrayHelper::map(\common\models\base\Restaurant::findAll(['user_id' => \Yii::$app->user->identity->id]), 'id', 'title');
        }
        ?>
        <div id="restaurant_div" class="form-group field-coupons-type required">
            <label class="control-label col-sm-3" for="coupons-type">Restaurant</label>
            <div class="col-sm-6">
                <?php
                $restaurant_id_edit = '';
                if (!$model->isNewRecord && $model->type == 'Restaurant') {
                    $restaurant_id_edit = $model->restaurantCoupons[0]->restaurant_id;
                }
                // Without model and implementing a multiple select
                echo Select2::widget([
                    'name' => 'restaurant',
                    'id' => 'restaurant',
                    'data' => $restaurant_array,
                    'value' => $restaurant_id_edit,
                    'options' => [
                        'placeholder' => 'Select rerstaurant ...',
                    ],
                ]);
                ?>
            </div>
        </div>

        <?php
        echo $form->field($model, 'expired_on')->widget(DateTimePicker::className([
                    'name' => 'dp_2',
                    'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                    'value' => '23-Feb-1982 10:01',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-M-yyyy hh:ii'
                    ]
        ]));
        ?>



        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Coupons',
                            'content' => $this->blocks['main'],
                            'active' => true,
                        ],]
                ]
        );
        ?>
        <hr/>

        <?=
        Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord ? 'Create' : 'Save'), [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
                ]
        );
        ?>


        <?php ActiveForm::end(); ?>

    </div>

</div>
<div style="display: none;">
    <?php
    echo Select2::widget([
        'name' => 'DSLFJ0DSFKL',
        'id' => 'EPRPOI08ER',
        'data' => [],
    ]);

    // Use a plain text input with no model, no ActiveField validation with both custom display and save formats.
    echo DateControl::widget([
        'name' => 'KHSDJSKDJS304985',
        'value' => '',
        'type' => DateControl::FORMAT_DATE,
        'displayFormat' => 'php:D, d-M-Y',
        'saveFormat' => 'php:Y-m-d',
        'options' => [
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]
    ]);
    ?>
</div>
<?php $this->registerJs($this->render('_form_js'), \yii\web\VIEW::POS_END); ?>
<?php
if (!$model->isNewRecord) {
    $this->registerJs("var coupon_id = " . $model->id . ";", \yii\web\VIEW::POS_END);
}
?>
