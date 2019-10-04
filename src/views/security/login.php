<?php
use kilyakus\module\user\widgets\Connect;
use yii\helpers\Html;

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?= $this->render('login_form',compact('model','module')) ?>
    </div>
</div>
<?php if ($module->enableConfirmation): ?>
    <p class="text-center">
        <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
    </p>
<?php endif ?>
<?php if ($module->enableRegistration): ?>
    <p class="text-center">
        <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
    </p>
<?php endif ?>
<?php 
// Connect::widget([
//     'baseAuthUrl' => ['/user/security/auth'],
// ])
?>