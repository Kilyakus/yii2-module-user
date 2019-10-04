<?php 
use kilyakus\web\widgets as Widget;
?>

<?= $this->render('_menu') ?>

<?php Widget\Portlet::begin([
    'title' => $this->title,
    // 'scroller' => [
    //     'max-height' => 50,
    //     'format' => 'vh',
    // ],
    'bodyOptions' => [
        'class' => 'kt-portlet__body--fit',
    ],
]); ?>
    <?= $content ?>
<?php Widget\Portlet::end(); ?>