<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\bootstrap\Button;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
/**
* @var yii\web\View $this
* @var common\models\Template $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="template-form">
      
      <?php if(\Yii::$app->session->hasFlash('success')):?>
      <?php echo "<div class='alert alert-success'>".\Yii::$app->session->getFlash('success')."</div>"; ?>
      <?php endif; ?>
     
      <?php if(\Yii::$app->session->hasFlash('warning')):?>
      <?php echo "<div class='alert alert-warning'>".\Yii::$app->session->getFlash('warning')."</div>"; ?>
      <?php endif; ?>
      
   <?php $this->beginBlock('main'); ?>
    <?php $form = ActiveForm::begin([
                        'id'     => 'Template',
                        'layout' => 'horizontal',
                        'enableClientValidation' => false,
                    ]
                );
    ?>  
     <div class="errr">
        <?php echo $form->errorSummary($model); ?>
     </div>
   <p>
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div class="pweb-field dropdown-check-list formquick-group "> 
					
					
      <table class="table items" id="mobile">
						  <thead>
							<tr>
							  <th style="width:6%"><input type="checkbox" id="checkAll"/></th>
							  <th>First Name</th>
							  <th>Last Name</th>
							  <th>Mobile Number</th>
							</tr>
						  </thead>
						 <tbody> 
							<?php
							  foreach($model1 as $val){
									echo "<tr>
									<th scope='row'><input type='checkbox' id='mobile_no' class='update-text-box' name='mobile_no[]' value='".$val['mobile_no']."'></th>
									<td>".$val['first_name']."</td>
									<td>".$val['last_name']."</td>
									<td>".$val['mobile_no']."</td>
									</tr>"; 
								  
							  }?> 
				    </tbody>
         </table> 
         
				</div>
			</div>
			<div class="col-md-3 mar-lft col-sm-6">
				<?= $form->field($model, 'sms')->textArea(['maxlength' => 150,'id'=>'sms-textarea','required'=>'required','rows'=>4, 'cols'=>50]) ?>  
			</div>
			<div class="col-md-3 dropdown-smstemplate-list col-sm-6">
				<ul class="items" id="template"> 
					 <?php $i=1; foreach($template as $templates){ ?>
				     <li class="templateTitle"> <?= ucwords($templates['title'])?> </li>
					 <li ><label id="template_<?= $i?>" class="template-sms"><?= $templates['sms']?></label> </li>
					 <?php $i++; } ?>
				 </ul>
			 </div>
        </div>	
        
         <hr/>

        <?= Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord
                            ? 'Send' : 'Save'),
                [
                    'id'    => 'save-' . $model->formName(),
                    'class' => 'btn btn-success'
                ]
            );
        ?>


         <?php ActiveForm::end(); ?>

   </p>
   <?php $this->endBlock(); ?>  
   
<?php  $this->beginBlock('mmm')?> 
	  <div class="errr" id="err">
       
       </div>  
  <p> 
	<form id='upload-form' name="upload-form"   method="post" enctype="multipart/form-data"> 
         <input type="file" name="file" id="file" style="width:auto;float:left"/>  
       <input type="button"  class="btn btn-success upload-form" value="upload">
     </form>   
   </p>
 <hr />
    <p id="sendsms" style="Display:none;"> 
   </p>
  <?php if($marketing){ ?>
   <p >
   <div class="row" id="sendmarkeing">
	<form method="post" name="send-sms-form-p" id="send-sms-form-p"> 
			<div class="col-md-6 col-sm-12">
				<div class="pweb-field dropdown-check-list formquick-group ">   
				   				
      <table class="table items" id="mobile">
						  <thead>
							<tr>
							  <th style="width:6%"><input type="checkbox" id="checkAllm"/></th>
							  <th> Name</th> 
							  <th>Mobile Number</th>
							</tr>
						  </thead>
						 <tbody> 
							<?php
							  foreach($marketing as $val){
									echo "<tr>
									<th scope='row'><input type='checkbox' id='mobile' class='update-text-box' name='mobile_no_m[]' value='".$val['mobile']."'></th>
									<td>".$val['name']."</td> 
									<td>".$val['mobile']."</td>
									</tr>"; 
								  
							  }?> 
				    </tbody>
         </table> 
				</div>
			</div>
			
			<div class="col-md-3 mar-lft col-sm-6">
				 
				<textarea required="required" id="sms-textarea_m" name="marketing" maxlength="150" cols="23" rows="4"></textarea> 
				  
			</div>
				<div class="col-md-3 dropdown-smstemplate-list col-sm-6">
				<ul id="template" class="items"> 
				   <?php $i=1; foreach($template as $templates){ ?>
					     <li class="templateTitle"> <?= ucwords($templates['title'])?> </li>
					 <li ><label id="template_<?= $i?>" class="template-sms_m"><?= $templates['sms']?></label> </li>
					 <?php $i++; } ?>    
			    </ul>
			 </div> 
		  <span class="glyphicon glyphicon-check">
			  <input type="button" value="Send" id="send-sms-p" class="btn btn-success">	 
			</span>  

  </form>
</div>
</p>
 <?php }?>
 <hr/> 
		
		
<?php $this->endBlock(); ?>
    
        <?=
        
        Tabs::widget([
    'items' => [
        [
            'label' => 'Send Group SMS',
            'content' => $this->blocks['main'],
        ],
        [
            'label' => 'Marketing',
            'content' => $this->blocks['mmm'],
            'options' => ['tag' => 'div'],
            'headerOptions' => ['class' => 'my-class'],
        ],
       
    ],
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['class' => 'my-class'],
    'clientOptions' => ['collapsible' => false],
]);
        
   /* Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'Send Grpup sms',
    'id'=>'group',
    'content' => $this->blocks['main'],
    'options' => ['id' => 'groupsmsID'],
    'active'  => true,
    'events' => array(
                        'click' => "js:goto()"
                        ),
],  
	['label'   => 'Marketing',
	'content' => $this->blocks['mmm'],
	'options' => ['id' => 'marketingID'],
	'events' => array(
                        'click' => "js:goto()"
                        ),
	]
]
                 ]
    );*/
    
    ?>
       
    </div> 
    
<?php $this->registerJs($this->render('_form_js'), \yii\web\VIEW::POS_END); ?>
 
