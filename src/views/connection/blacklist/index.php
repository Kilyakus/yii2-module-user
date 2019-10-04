<?php
use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = Yii::t('user','Black list');
$this->params['breadcrumbs'][] = ['url' => Url::toRoute(['/user/profile/show','id' => $profile->user->id]), 'label' => Yii::t('user','Profile')];
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

<?php $this->beginContent('@bin/user/views/layouts/main.php') ?>
    <div class="panel panel-default border">
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
    </div>
<?php $this->endContent() ?>