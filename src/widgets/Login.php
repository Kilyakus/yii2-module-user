<?php
namespace kilyakus\module\user\widgets;

use kilyakus\module\user\models\LoginForm;
use yii\base\Widget;

class Login extends Widget
{
    public $validate = true;

    public function run()
    {
        return $this->render('login', [
            'model' => \Yii::createObject(LoginForm::className()),
        ]);
    }
}
