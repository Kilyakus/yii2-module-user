<?php
use yii\helpers\Url;
use kilyakus\web\widgets as Widget;
?>

<?= Widget\Nav::widget([
    'options' => [
        'class' => 'nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-line-2x nav-tabs-line-right',
        'role' => 'tablist'
    ],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => '<i class="fa fa-user"></i>' . Yii::t('user', 'Information'),
            'url' => Url::toRoute(['/user/admin/info', 'id' => $user->id]),
            'options' => ['class' => (Url::to() == Url::toRoute(['/user/admin/info', 'id' => $user->id])) ? 'active' : '']
        ],
        [
            'label' => '<i class="fa fa-user-edit"></i>' . Yii::t('user', 'Profile details'),
            'url' => Url::toRoute(['/user/admin/update-profile', 'id' => $user->id]),
            'options' => ['class' => (Url::to() == Url::toRoute(['/user/admin/update-profile', 'id' => $user->id])) ? 'active' : '']
        ],
        [
            'label' => '<i class="fa fa-user-shield"></i>' . Yii::t('user', 'Account details'),
            'url' => Url::toRoute(['/user/admin/update', 'id' => $user->id]),
            'visible' => IS_ADMIN,
            'options' => ['class' => (Url::to() == Url::toRoute(['/user/admin/update', 'id' => $user->id])) ? 'active' : '']
        ],
        [
            'label' => Yii::t('user', 'Assignments'),
            'url' => Url::toRoute(['/user/admin/assignments', 'id' => $user->id]),
            'visible' => false,
            'options' => ['class' => (Url::to() == Url::toRoute(['/user/admin/assignments', 'id' => $user->id])) ? 'active' : '']
        ],
        [
            'label' => '<i class="fa fa-user-check text-success"></i>' . Yii::t('user', 'Confirm'),
            'url' => Url::toRoute(['/user/admin/confirm', 'id' => $user->id]),
            'visible' => !$user->isConfirmed,
            'linkOptions' => [
                'class' => 'text-success',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
            ],
        ],
        [
            'label' => '<i class="fa fa-lock text-danger"></i>' . Yii::t('user', 'Block'),
            'url' => Url::toRoute(['/user/admin/block', 'id' => $user->id]),
            'visible' => !$user->isBlocked,
            'linkOptions' => [
                'class' => 'text-danger',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
            ],
        ],
        [
            'label' => '<i class="fa fa-lock-open text-success"></i>' . Yii::t('user', 'Unblock'),
            'url' => Url::toRoute(['/user/admin/block', 'id' => $user->id]),
            'visible' => $user->isBlocked,
            'linkOptions' => [
                'class' => 'text-success',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
            ],
        ],
        [
            'label' => '<i class="fa fa-user-times text-danger"></i>' . Yii::t('user', 'Delete'),
            'url' => Url::toRoute(['/user/admin/delete', 'id' => $user->id]),
            'visible' => IS_ROOT,
            'linkOptions' => [
                'class' => 'text-danger',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?'),
            ],
        ],
    ],
]) ?>

<?= Widget\Nav::widget([
    'options' => [
        'class' => 'nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-line-2x nav-tabs-line-right',
        'role' => 'tablist'
    ],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => '<i class="glyphicon glyphicon-user"></i>',
            'url' => Url::to(['/user/admin/switch', 'id' => $user->id]),
            'linkOptions' => ['data-method' => 'POST', 'data-confirm' => Yii::t('user', 'Are you sure you want to switch to this user for the rest of this Session?'), 'title' => Yii::t('user', 'Become this user')],
            'visible' => (Yii::$app->user->identity->isAdmin && $model->id != Yii::$app->user->id && Yii::$app->getModule('user')->enableImpersonateUser)
        ],
        [
            'label' => '<i class="glyphicon glyphicon-transfer"></i>',
            'url' => Url::to(['resend-password', 'id' => $user->id]),
            'linkOptions' => ['data-method' => 'POST', 'data-confirm' => Yii::t('user', 'Are you sure?'), 'title' => Yii::t('user', 'Generate and send new password to user')],
            'visible' => (Yii::$app->user->identity->isAdmin && !$user->isAdmin)
        ],
    ]
]) ?>