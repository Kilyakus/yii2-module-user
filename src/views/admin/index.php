<?php
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\Pjax;
use kilyakus\web\widgets as Widget;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \kilyakus\module\user\models\UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;
$usernames = [];
$useremail = [];
$userroles = [];
foreach ($dataProvider->query->all() as $item) {
    $usernames[$item->username] = $item->username;
    $useremail[$item->email] = $item->email;
    $userroles[$item->role] = $item->role;
}
?>

<?php $this->beginContent('@bin/user/views/admin/layout.php') ?>

<?php Pjax::begin(['enablePushState' => false]) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'layout'       => "{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-hover'],
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:90px;'], # 90px is sufficient for 5-digit user ids
        ],
        [
            'attribute' => 'avatar',
            'header'    => Yii::t('user', 'Avatar'),
            'headerOptions' => ['style' => 'width:70px;'],
            'value' => function ($model) {
                return Html::img($model->avatar,['class' => 'img-thumbnail img-circle kt-img-rounded']);
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'username',
            'header'    => Yii::t('user', 'Username'),
            'value' => function ($model) {
                return Html::a(Html::encode($model->username), Url::to(['info', 'id' => $model->id]),['data-pjax' => 0]);
            },
            'format' => 'raw',
            'filter' => Widget\Select2::widget([
                'model'     => $searchModel,
                'attribute' => 'username',
                'data'      => $usernames,
                'options'   => [
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]),
        ],
        [
            'attribute' => 'email',
            'header'    => Yii::t('user', 'Email'),
            'filter' => Widget\Select2::widget([
                'model'     => $searchModel,
                'attribute' => 'email',
                'data'      => $useremail,
                'options'   => [
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]),
        ],
        [
            'attribute' => 'role',
            'label' => Yii::t('forum/view', 'Role'),
            'filter' => Widget\Select2::widget([
                'model'     => $searchModel,
                'attribute' => 'role',
                'data'      => \bin\forum\src\models\User::getRoles(),
                'options'   => [
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]),
            'value' => function ($model) {
                return ArrayHelper::getValue(\bin\forum\src\models\User::getRoles(), $model->role);
            },
        ],
        [
            'attribute' => 'registration_ip',
            'value' => function ($model) {
                return $model->registration_ip == null
                    ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                    : $model->registration_ip;
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                if (extension_loaded('intl')) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                } else {
                    return date('Y-m-d G:i:s', $model->created_at);
                }
            },
        ],

        [
            'attribute' => 'last_login_at',
            'value' => function ($model) {
                if (!$model->last_login_at || $model->last_login_at == 0) {
                    $response = Yii::t('user', 'Never');
                } else if (extension_loaded('intl')) {
                    $response = Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->last_login_at]);
                } else {
                    $response = date('Y-m-d G:i:s', $model->last_login_at);
                }
                return $model->getOnline() . ' ' . $response;
            },
            'format' => 'raw',
        ],
        [
            'header' => Yii::t('user', 'Confirmation'),
            'value' => function ($model) {
                if ($model->isConfirmed) {
                    return '<div class="text-center">
                                <span class="text-success">' . Yii::t('user', 'Confirmed') . '</span>
                            </div>';
                } else {
                    return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                    ]);
                }
            },
            'format' => 'raw',
            'visible' => Yii::$app->getModule('user')->enableConfirmation,
        ],
        [
            'header' => Yii::t('user', 'Block status'),
            'value' => function ($model) {
                if ($model->isBlocked) {
                    return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                    ]);
                    // return Html::checkbox(Yii::t('user', 'Block'), $model->blocked_at, [
                    //     'class' => 'switch',
                    //     'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                    //     'data-method' => 'post',
                    //     'data-link' => Url::to(['block', 'id' => $model->id]),
                    // ]);
                } else {
                    return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                    ]);
                }
            },
            'format' => 'raw',
        ],
        // [
        //     'class' => 'yii\grid\ActionColumn',
        //     'template' => '{send_letter} {switch} {resend_password} {update} {delete}',
        //     'buttons' => [
        //         'send_letter' => function ($url, $model, $key) {
        //             // if (!$model->isAdmin) {
        //             //     return '
        //             // <a data-method="POST" href="' . Url::to(['resend-password', 'id' => $model->id]) . '">
        //             // <span title="' . Yii::t('user', 'Generate and send new password to user') . '" class="glyphicon glyphicon-envelope">
        //             // </span> </a>';
        //             // }
        //         },
        //         'resend_password' => function ($url, $model, $key) {
        //             if (\Yii::$app->user->identity->isAdmin && !$model->isAdmin) {
        //                 return '
        //             <a data-method="POST" data-confirm="' . Yii::t('user', 'Are you sure?') . '" href="' . Url::to(['resend-password', 'id' => $model->id]) . '">
        //             <span title="' . Yii::t('user', 'Generate and send new password to user') . '" class="glyphicon glyphicon-transfer">
        //             </span> </a>';
        //             }
        //         },
        //         'switch' => function ($url, $model) {
        //             if(\Yii::$app->user->identity->isAdmin && $model->id != Yii::$app->user->id && Yii::$app->getModule('user')->enableImpersonateUser) {
        //                 return Html::a('<span class="glyphicon glyphicon-user"></span>', ['/user/admin/switch', 'id' => $model->id], [
        //                     'title' => Yii::t('user', 'Become this user'),
        //                     'data-confirm' => Yii::t('user', 'Are you sure you want to switch to this user for the rest of this Session?'),
        //                     'data-method' => 'POST',
        //                 ]);
        //             }
        //         }
        //     ]
        // ],
    ],
]); ?>

<?php Pjax::end() ?>

<?php $this->endContent() ?>