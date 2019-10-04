<?php
use app\assets\UserAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bin\admin\helpers\Image;
use bin\admin\components\API;

$baseUrl = UserAsset::register($this)->baseUrl;
?>

<img src="<?= Image::thumb($model->image,120,80) ?>">