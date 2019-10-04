<?php
use app\assets\UserAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
$baseUrl = UserAsset::register($this)->baseUrl;

$this->title = 'Чат';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
html,
body {width:100%;overflow-x:hidden;}
</style>
<?php $this->beginContent('@bin/user/views/layouts/main.php') ?>

<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
    <?= $this->render('@bin/admin/modules/chat/widgets/chat/views/_contacts',['path' => Url::toRoute(['/user/connection']), 'id' => $id, 'expand' => $expand]) ?>

    <?= \bin\admin\modules\chat\widgets\chat\ChatPrivate::widget(['path' => Url::toRoute(['/user/connection']), 'id' => $id, 'expand' => $expand]) ?>
</div>
<?php $this->endContent() ?>