<?php
use app\assets\ScrollbarAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use bin\admin\modules\chat\models\Message;
use bin\admin\modules\chat\models\Relative;
ScrollbarAsset::register($this);

$id = $currentId ? $currentId : $id;

if ($id > 0) {
    Message::updateAll(['viewed'=>time()],['recipientId'=>Yii::$app->user->id,'authorId'=>$id]);
}
?>

<div class="mCustomScrollbar" data-mcs-theme="default">
    <div class="panel-body">
        <?php if ($dataProvider == ''): ?>
            <div class="alert bg-warning text-warning">Выберите диалог</div>
        <?php else: ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_message',['model'=>$model]);
                },
                'layout' => "{items}",
            ]) ?>
        <?php endif ?>
    </div>
</div>
<?php if ($dataProvider != ''): ?>
    <table class="table mb-0" style="position:absolute;bottom:0px;">
        <?php if ($dataProvider != ''): ?>
            <tbody>
                <tr class="hidden">
                    <td>
                        <?php if ($id > 0): ?>
                            <div class="link-left-items">
                                <?= Relative::checkFriend($id) ?>
                                <?= Relative::checkFavorite($id) ?>
                                <?= Relative::checkBlackList($id) ?>
                            </div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#F8FBFE;padding:0px 5px;">
                        <?php $form = ActiveForm::begin([
                            'action'=> Url::to(['/user/connection/chat','id' => $id]),
                            'id'=>'message-form-'.$id,
                            'options' => ['data-pjax' => true, 'data-pjax-problem' => true,'class' => 'm-0']
                        ]); ?>
                            <table class="table mb-0" style="background-color:transparent;">
                                <td width="100%">
                                    <?= $form->field($message, 'recipientId',['options' => ['class' => 'hidden']])->hiddenInput(['id' => 'recipientId'.$id,'value'=>$id])->label(false) ?>
                                    <?= $form->field($message, 'text')->textarea(['id' => 'text'.$id])->label(false) ?>
                                </td>
                                <td width="65px">
                                    <?= Html::submitButton('', ['class' => 'btn btn-lg btn-primary btn-block paper-plane']) ?>
                                </td>
                            </table>
                        <?php ActiveForm::end(); ?>
                    </td>
                </tr>
            </tbody>
        <?php endif ?>
    </table>
<?php endif ?>