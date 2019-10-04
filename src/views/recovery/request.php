<?php
use yii\helpers\Html;

$this->title = Yii::t('user', 'Recover your password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?= $this->render('request_form',compact('model')) ?>
    </div>
</div>