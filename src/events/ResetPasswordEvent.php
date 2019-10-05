<?php
namespace kilyakus\module\user\events;

use kilyakus\module\user\models\RecoveryForm;
use kilyakus\module\user\models\Token;
use yii\base\Event;

class ResetPasswordEvent extends Event
{
    private $_form;
    private $_token;

    public function getToken()
    {
        return $this->_token;
    }

    public function setToken(Token $token = null)
    {
        $this->_token = $token;
    }

    public function getForm()
    {
        return $this->_form;
    }

    public function setForm(RecoveryForm $form = null)
    {
        $this->_form = $form;
    }
}
