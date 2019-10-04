<?php
use app\assets\UserAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use kilyakus\module\user\models\User;
use bin\admin\modules\chat\models\Group;
use bin\admin\modules\chat\models\Relative;
use yii\web\JsExpression;
$baseUrl = UserAsset::register($this)->baseUrl;

$this->title = 'Чат';
$this->params['breadcrumbs'][] = $this->title;

$admin = (($id <= 0) ?: Group::findOne($id)->adminId);

$users = [];

if($admin){
    $item = User::findOne($admin);

    if($item){
        $users[] = [
            'label' => $item->abbreviate,
            'image' => $item->avatar,
            'options' => ['title' => $item->abbreviate]
        ];
    }
}

foreach (Relative::findAll(['groupId'=>$id]) as $key => $item) {
    $item = User::findOne($item->userId);

    if($item) {
        $users[] = [
            'label' => $item->abbreviate,
            'image' => $item->avatar,
        'options' => ['title' => $item->abbreviate]
        ];
    }
}
?>
<style type="text/css">
html,
body {width:100%;overflow-x:hidden;}
</style>
<?php $this->beginContent('@bin/user/views/layouts/main.php') ?>

<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
    <?= $this->render('@bin/admin/modules/chat/widgets/chat/views/_contacts',['path' => $path, 'id' => $id, 'expand' => $expand]) ?>

    <?= \bin\admin\modules\chat\widgets\chat\ChatPrivate::widget(['path' => $path, 'id' => $id, 'expand' => $expand, 'users' => $users, 'admin' => $admin]) ?>
</div>
<?php $this->endContent() ?>