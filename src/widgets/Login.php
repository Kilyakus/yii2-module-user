<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/bin>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace kilyakus\module\user\widgets;

use kilyakus\module\user\models\LoginForm;
use yii\base\Widget;

/**
 * Login for widget.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Login extends Widget
{
    /**
     * @var bool
     */
    public $validate = true;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('login', [
            'model' => \Yii::createObject(LoginForm::className()),
        ]);
    }
}
