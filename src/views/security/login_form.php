<?php
use kilyakus\module\user\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'method' => 'POST', 
    'options' => ['enctype' => 'multipart/form-data'],
    'action' => ['/user/security/login'],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]) ?>

<?php if ($module->debug): ?>
    <?= $form->field($model, 'login', [
        'inputOptions' => [
            'autofocus' => 'autofocus',
            'class' => 'form-control input-lg',
            'tabindex' => '1'
        ]
    ])->dropDownList(LoginForm::loginList());
    ?>

<?php else: ?>

    <?= $form->field($model, 'login',
        ['inputOptions' => [
            // 'autofocus' => 'autofocus',
            'class' => 'form-control input-lg',
            'tabindex' => '1',
            'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Login') . ' ' . Yii::t('easyii','or') . ' ' . Yii::t('user','E-mail')
        ]
    ]); ?>

<?php endif ?>

<?php if ($module->debug): ?>
    <div class="alert alert-warning">
        <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
    </div>
<?php else: ?>
    <?= $form->field(
        $model,
        'password',
        ['inputOptions' => ['class' => 'form-control input-lg', 'tabindex' => '2', 'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Password'),]])
        ->passwordInput()
        ->label(
            Yii::t('user', 'Password')
            . ($module->enablePasswordRecovery ?
                ' (' . Html::a(
                    Yii::t('user', 'Forgot password?'),
                    ['/user/recovery/request'],
                    ['tabindex' => '5']
                )
                . ')' : '')
        ) ?>
<?php endif ?>

<?= $form->field($model, 'rememberMe')->checkbox(['class' => 'switch', 'tabindex' => '3', 'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>"]) ?>

<div class="row h-align-lg">
    <div class="col-lg-7 col-sm-5 col-xs-12">
        <?= Html::submitButton(
    Yii::t('user', 'Sign in'),
    ['class' => 'btn btn-lg btn-primary btn-block', 'tabindex' => '4']
) ?>
    </div>
    <div class="col-lg-5 col-sm-7 col-xs-12 h-align-xs pt-xs-20">
        <?= \app\widgets\Socials\Socials::widget() ?>
    </div>
</div>

<?php ActiveForm::end(); ?>