<?php

namespace kilyakus\module\user\traits;

use kilyakus\module\user\UserModule;

trait ModuleTrait
{
    public function getModule()
    {
        return \Yii::$app->getModule('user');
    }

    public static function getDb()
    {
        return \Yii::$app->getModule('user')->getDb();
    }
}
