<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'id' => 'password-recovery-form',
    'method' => 'POST', 
    'action' => ['/user/recovery/request'],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'options' => ['class' => 'h-align-md h-align-lg']
]); ?>
<label></label>
<?= $form->field($model, 'email',[
    'options' => [
        'class' => 'mr-15 w-12'
    ],
    // 'template' => '{label}{input}',
])->input('text',[
    'autofocus' => true,
    'class' => 'form-control input-lg',
    'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','E-mail')
])->label(false) ?>

<?= Html::submitButton(Yii::t('easyii', 'Send password'), ['class' => 'btn btn-lg btn-primary']) ?>

<?php ActiveForm::end(); ?>