<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/bin>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
use yii\helpers\Url;
use kilyakus\web\widgets as Widget;
?>

<?= Widget\NavPage::widget([
    'options' => [
        'class' => 'nav-pills',
    ],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => '<i class="fa fa-users"></i>&nbsp; ' . Yii::t('user', 'Users'),
            'url' => Url::toRoute(['/user/admin']),
            'active' => (Url::to() == Url::toRoute(['/user/admin']))
        ],
        [
            'label' => '<i class="fa fa-code-branch"></i>&nbsp; ' . Yii::t('user', 'Roles'),
            'url' => Url::toRoute(['/rbac/role']),
            'active' => (Url::to() == Url::toRoute(['/rbac/role'])),
            'visible' => IS_ROOT
        ],
        [
            'label' => '<i class="fa fa-sitemap"></i>&nbsp; ' . Yii::t('user', 'Permissions'),
            'url' => Url::toRoute(['/rbac/permission']),
            'active' => (Url::to() == Url::toRoute(['/rbac/permission'])),
            'visible' => IS_ROOT
        ],
        [
            'label' => '<i class="glyphicon glyphicon-certificate"></i>&nbsp; ' . Yii::t('user', 'Tariffs'),
            'url' => Url::toRoute(['/rbac/tariff']),
            'active' => (Url::to() == Url::toRoute(['/rbac/tariff'])),
            'visible' => IS_ROOT
        ],
        // [
        //     'label' => \Yii::t('user', 'Rules'),
        //     'url'   => ['/admin/rule'],
        //     'options' => ['class' => ($url == 'system/rule/index') ? 'active' : '']
        // ],
        [
            'label' => Yii::t('user', 'Create'),
            'visible' => IS_ROOT,
            'items' => [
                [
                    'label' => Yii::t('user', 'New user'),
                    'url' => Url::toRoute(['/user/admin/create']),
                    'options' => ['class' => (Url::to() == Url::toRoute(['/user/admin/create'])) ? 'active' : ''],
                ],
                [
                    'label' => Yii::t('user', 'New role'),
                    'url' => Url::toRoute(['/rbac/role/create']),
                    'options' => ['class' => (Url::to() == Url::toRoute(['/rbac/role/create'])) ? 'active' : '']
                ],
                [
                    'label' => Yii::t('user', 'New permission'),
                    'url' => Url::toRoute(['/rbac/permission/create']),
                    'options' => ['class' => (Url::to() == Url::toRoute(['/rbac/permission/create'])) ? 'active' : ''],
                ],
                [
                    'label' => Yii::t('user', 'New tariff'),
                    'url' => Url::toRoute(['/rbac/tariff/create']),
                    'options' => ['class' => (Url::to() == Url::toRoute(['/rbac/tariff/create'])) ? 'active' : ''],
                ],
                // [
                //     'label' => \Yii::t('user', 'New rule'),
                //     'url'   => Url::toRoute(['/system/rule/create']),
                //     'options' => ['class' => ($url == 'system/rule/create') ? 'active' : '']
                // ]
            ],
        ],
        [
            'label' => \Yii::t('user', 'Import'),
            'url'   => '/admin/import',
            'options' => ['class' => ($url == '/admin/import') ? 'active pull-right' : 'pull-right'],
            'visible' => IS_ROOT
        ],
        [
            'label' => '<i class="glyphicon glyphicon-cog"></i> ' . Yii::t('easyii', 'Settings'),
            'url' => Url::toRoute(['/rbac/agreements']),
            'options' => ['class' => (Url::to() == Url::toRoute(['/rbac/agreements'])) ? 'active pull-right' : 'pull-right']
        ],
    ],
]) ?>
