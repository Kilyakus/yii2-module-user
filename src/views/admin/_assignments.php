<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/bin>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use bin\rbac\widgets\Assignments;
use bin\rbac\widgets\ForumAssignments;

/**
 * @var yii\web\View $this
 * @var kilyakus\module\user\models\User $user
 */
?>

<?php $this->beginContent('@bin/user/views/admin/update.php', ['user' => $user]) ?>

<?= yii\bootstrap\Alert::widget([
    'options' => [
        'class' => 'alert-info alert-dismissible',
    ],
    'body' => Yii::t('user', 'You can assign multiple roles or permissions to user by using the form below'),
]) ?>

<?= Assignments::widget(['userId' => $user->id]) ?>

<?= ForumAssignments::widget(['userId' => $user->id]) ?>

<?php $this->endContent() ?>
