<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/bin/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kilyakus\module\user;

use Yii;
use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements \dektrium\user\Bootstrap
{

}
