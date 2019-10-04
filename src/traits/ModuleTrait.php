<?php

namespace kilyakus\module\user\traits;

use kilyakus\module\user\Module;

/**
 * Trait ModuleTrait
 *
 * @property-read Module $module
 * @package kilyakus\module\user\traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('user');
    }

    /**
     * @return string
     */
    public static function getDb()
    {
        return \Yii::$app->getModule('user')->getDb();
    }
}
