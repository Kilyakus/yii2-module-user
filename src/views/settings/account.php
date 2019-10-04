<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'Account settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user','Profile'), 'url' => Url::toRoute(['/user/profile/show'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<?php $form = ActiveForm::begin([
    'id' => 'account-form',
    'options' => ['class' => 'model-form'],
    // 'fieldConfig' => [
    //     'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
    //     'labelOptions' => ['class' => 'col-lg-3 control-label'],
    // ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>

<?= $form->field($model, 'email')->textInput(['class' => 'input-lg form-control']) ?>

<?= $form->field($model, 'username')->textInput(['class' => 'input-lg form-control']) ?>

<?= $form->field($model, 'new_password')->passwordInput(['class' => 'input-lg form-control']) ?>

<hr/>

<?= $form->field($model, 'current_password')->passwordInput(['class' => 'input-lg form-control']) ?>

<div class="row">
    <div class="col-xs-12 col-md-3">
        <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-lg btn-block btn-primary']) ?><br>
    </div>
    <div class="col-xs-12 col-md-3">
        <?= Html::a(Yii::t('user', 'Networks'),Url::toRoute(['/user/settings/networks']), ['class' => 'btn btn-lg btn-block btn-primary']) ?><br>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php if ($model->module->enableAccountDelete): ?>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('user', 'Delete account') ?></h3>
        </div>
        <div class="panel-body">
            <p>
                <?= Yii::t('user', 'Once you delete your account, there is no going back') ?>.
                <?= Yii::t('user', 'It will be deleted forever') ?>.
                <?= Yii::t('user', 'Please be certain') ?>.
            </p>
            <?= Html::a(Yii::t('user', 'Delete account'), ['delete'], [
                'class' => 'btn btn-danger',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Are you sure? There is no going back'),
            ]) ?>
        </div>
    </div>
<?php endif ?>