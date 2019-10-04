<?php
namespace kilyakus\module\user\widgets;

use Yii;
use yii\helpers\Url;
use yii\base\Widget;
use bin\admin\helpers\Image;
use kilyakus\module\user\models\User;

class UserDetails extends Widget
{
    public static function get($id = null)
    {
        if($id === null){
            $id = Yii::$app->user->identity->id;
        }

        $user = User::findOne($id);

        if(!$user){
            $user = new User;
        }

        return $user;
    }
}
