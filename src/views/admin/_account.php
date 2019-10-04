<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/bin>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var kilyakus\module\user\models\User $user
 */
?>

<?php $this->beginContent('@bin/user/views/admin/update.php', ['user' => $user]) ?>
<div class="row">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => ['class' => 'form-horizontal col-xs-12 col-sm-12 col-md-9'],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $this->render('_user', ['form' => $form, 'user' => $user]) ?>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php $this->endContent() ?>
