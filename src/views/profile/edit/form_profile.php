<?php
use bin\admin\components\API;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<?php Pjax::begin([
    'enablePushState' => false,
    'options' => ['class' => 'pt-25']
]);?>
    <h3 class="mt-25"><?= API::getName() ?></h3>
    <p>
        <?php foreach (Yii::$app->authManager->getRolesByUser(Yii::$app->user->id) as $status) : ?>
            <i class="text-gray"><?= $status->description; ?></i>
        <?php endforeach; ?>
    </p>
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true, 'data-pjax-problem' => true, 'class' => 'model-form pt-25',],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        // 'validateOnBlur' => true,
    ]); ?>
    <div class="row">
        <?= $form->field($profile, 'name',['options' => ['class' => 'form-group col-xs-12 col-md-4']])->input('text',['class' => 'form-control input-lg']) ?>

        <?= $form->field($user, 'email',['options' => ['class' => 'form-group col-xs-12 col-md-4']])->input('text',['class' => 'form-control input-lg']) ?>
        <div class="col-xs-12 col-md-4">
            <label>&nbsp;</label><br>
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-lg btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>