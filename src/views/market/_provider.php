<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Modal;
use bin\admin\helpers\Image;
?>

<table class="table table-xs mb-0">
    <thead>
        <tr>
            <th width="50"></th>
            <th><?= Yii::t('easyii','Title') ?></th>
            <th><?= Yii::t('easyii','Category') ?></th>
            <th><?= Yii::t('easyii','Status') ?></th>
            <th width="120"></th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($items)) : ?>
            <?php foreach($items as $item) : ?>
                <tr class="alert <?= ($item->model->status == 1) ? 'alert-default' : ($item->model->status == 0 ? 'alert-info' : 'alert-warning'); ?>">
                    <td>
                        <?= Html::img(Image::thumb($item->preview, 30, 30),['class' => 'img-circle']) ?>
                    </td>
                    <td>
                        <a href="<?= Url::toRoute(['/market/view','slug' => $item->slug]) ?>"><?= $item->title ?></a>
                    </td>
                    <td>
                        <span class="text-muted">(<?= $item->model->category->title ?>)</span>
                    </td>
                    <td>
                        <?= ($item->model->status == 1) ? 'Активно' : ($item->model->status == 2 ? 'Не активно' : 'На модерации'); ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm btn-default" href="<?= Url::toRoute(['/market/view','slug' => $item->slug]) ?>" title="<?= Yii::t('easyii','View') ?>"><i class="glyphicon glyphicon-eye-open font-12"></i></a>
                            <a class="btn btn-sm btn-default" href="<?= Url::toRoute(['/page/market/items/edit','id' => $item->id]) ?>" title="<?= Yii::t('easyii','Edit') ?>"><i class="glyphicon glyphicon-pencil font-12"></i></a>
                            <?php Modal::begin([
                                'header' => '<h4>' . Yii::t('easyii','Select an action') . '</h4>',
                                'toggleButton' => ['label' => '<i class="glyphicon glyphicon-cog"></i>', 'class' => 'btn btn-sm btn-default'],
                            ]); ?>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <a href="<?= Url::to(['/page/market/items/archive', 'id' => $item->id]) ?>" class="btn btn-lg btn-primary btn-block">
                                            <i class="glyphicon glyphicon-folder-open"></i>&nbsp; <?= Yii::t('easyii', 'Move to archive') ?>
                                        </a>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <a href="<?= Url::to(['/page/market/items/delete', 'id' => $item->id]) ?>" class="btn btn-lg btn-danger btn-block confirm-delete" data-reload="1">
                                            <span class="text-white"><i class="glyphicon glyphicon-trash font-12"></i> <?= Yii::t('easyii', 'Delete record') ?></span>
                                        </a>
                                    </div>
                                </div>
                            <?php Modal::end(); ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="alert alert-info">
                <td colspan="5" style="height:50px;">
                    <?= Yii::t('easyii','No records found') ?>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
// ListView::widget([
//     'dataProvider' => \bin\admin\modules\market\models\Item::find()->where(['and',['created_by' => Yii::$app->user->identity->id],['status' => [0,1]]]),
//     'layout' => "{items}\n{pager}",
//     'itemView' => function ($model, $key, $index, $widget) {
//         return $this->render('_item',['model'=>$model]);
//     },
// ]) 
?>