<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use bin\admin\helpers\Image;
use bin\admin\modules\catalog\api\Catalog;
?>

<table class="table table-xs">
    <thead>
        <tr>
            <th width="50"></th>
            <th width="300"><?= Yii::t('easyii','Category') ?></th>
            <th><?= Yii::t('easyii','Title') ?></th>
            <th><?= Yii::t('easyii','Status') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach(Catalog::items(['where' => ['created_by' => Yii::$app->user->identity->id]]) as $item) : ?>
            <?php $category = Catalog::cat($item->category_id); ?>
            <tr class="<?= ($item->model->status == 0) ? 'alert alert-info' : ''; ?>">
                <td>
                    <?= Html::img(Image::thumb($item->preview, 30, 30)) ?>
                </td>
                <td>
                    <?= $category->title ?>
                </td>
                <td>
                    <a href="<?= Url::toRoute(['/catalog/view','slug' => $item->slug]) ?>"><?= $item->title ?></a>
                </td>
                <td>
                    <?= ($item->model->status == 0) ? 'На модерации' : 'Активно'; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
// ListView::widget([
//     'dataProvider' => Category::findAll($category->category_id),
//     'layout' => "{items}\n{pager}",
//     'itemView' => function ($model, $key, $index, $widget) {
//         return $this->render('_item',['model'=>$model,'message' => $message]);
//     },
// ]) 
?>