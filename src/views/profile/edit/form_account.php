<?php
use bin\admin\components\API;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<div class="row mt-25">
    <div class="col-xs-12 col-md-8">
        <?php Pjax::begin([
            'enablePushState' => false,
        ]);?>
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'model-form', 'data-pjax' => true, 'data-pjax-problem' => true,],
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validateOnChange' => true,
            ]); ?>

                <?= $form->field($account, 'current_password')->passwordInput(['class' => 'form-control input-lg']) ?>

                <?= $form->field($account, 'new_password')->passwordInput(['class' => 'form-control input-lg']) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-lg btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>
    </div>
</div>