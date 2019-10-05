<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php if (Yii::$app->user->isGuest): ?>
    <?php $form = ActiveForm::begin([
        'id'                     => 'login-widget-form',
        'action'                 => Url::to(['/user/security/login']),
        'enableAjaxValidation'   => true,
        'enableClientValidation' => false,
        'validateOnBlur'         => false,
        'validateOnType'         => false,
        'validateOnChange'       => false,
    ]) ?>

    <?= $form->field($model, 'login')->textInput(['placeholder' => 'Login']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-primary btn-block']) ?>

    <?php ActiveForm::end(); ?>
<?php else: ?>
    <?= Html::a(Yii::t('user', 'Logout'), ['/user/security/logout'], [
        'class'       => 'btn btn-danger btn-block',
        'data-method' => 'post'
    ]) ?>
<?php endif ?>
