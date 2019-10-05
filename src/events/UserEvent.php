<?php
namespace kilyakus\module\user\events;

use kilyakus\module\user\models\User;
use yii\base\Event;

class UserEvent extends Event
{
    private $_user;

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser(User $form)
    {
        $this->_user = $form;
    }
}
