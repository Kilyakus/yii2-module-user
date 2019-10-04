<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kilyakus\web\widgets as Widget;
use bin\rbac\widgets\Assignments;
use bin\rbac\widgets\ForumAssignments;
?>

<?php $this->beginContent('@bin/user/views/admin/update.php', ['user' => $user]) ?>

<?php if ($user->registration_ip !== null): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><strong><?= Yii::t('user', 'Registration IP') ?>:</strong></div>
        <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9"><?= $user->registration_ip ?></div>
    </div>
    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-sm"></div>
<?php endif ?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><strong><?= Yii::t('user', 'Registration time') ?>:</strong></div>
    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9"><?= Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$user->created_at]) ?></div>
</div>
<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-sm"></div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><strong><?= Yii::t('user', 'Confirmation status') ?>:</strong></div>
    <?php if ($user->isConfirmed): ?>
        <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9 text-success">
            <?= Yii::t('user', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$user->confirmed_at]) ?>
        </div>
    <?php else: ?>
        <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9 text-danger">
            <?= Yii::t('user', 'Not confirmed') ?>
        </div>
    <?php endif ?>
</div>
<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-sm"></div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><strong><?= Yii::t('user', 'Last login') ?>:</strong></div>
    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9 text-success">
        <?php if (!$user->last_login_at || $user->last_login_at == 0) {
            $response = '<span class="text-danger">'.Yii::t('user', 'Never').'</span>';
        } else if (extension_loaded('intl')) {
            $response = Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$user->last_login_at]);
        } else {
            $response = date('Y-m-d G:i:s', $user->last_login_at);
        } ?>
        <?= $response; ?>
    </div>
</div>
<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-sm"></div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><strong><?= Yii::t('user', 'Block status') ?>:</strong></div>
    <?php if ($user->isBlocked): ?>
        <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9 text-danger">
            <?= Yii::t('user', 'Blocked at {0, date, MMMM dd, YYYY HH:mm}', [$user->blocked_at]) ?>
        </div>
    <?php else: ?>
        <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9 text-success">
            <?= Yii::t('user', 'Not blocked') ?>
        </div>
    <?php endif ?>
</div>

<?php $this->endContent() ?>
