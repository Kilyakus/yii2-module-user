<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$usernames = [];
$useremail = [];
$userroles = [];
foreach ($dataProvider->query->all() as $item) {
    $usernames[$item->username] = $item->username;
    $useremail[$item->email] = $item->email;
    $userroles[$item->role] = $item->role;
}
?>
<form class="search-friends">
    <input type="text" placeholder="Поиск по друзьям" />
    <button type="submit"><i class="glyphicon glyphicon-search"></i></button>
    <div class="search-filter">
        Фильтр
    </div>
</form>
<div class="panel-body">
    <table class="table table-xs">
        <tbody>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_item',['model'=>$model,'message' => $message]);
                },
            ]) ?>
        </tbody>
    </table>
</div>