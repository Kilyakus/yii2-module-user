<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bin\admin\helpers\Image;
use bin\admin\components\API;
?>

<img src="<?= Image::thumb($model->image,120,80) ?>">