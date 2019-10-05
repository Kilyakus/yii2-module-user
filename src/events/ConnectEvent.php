<?php
namespace kilyakus\module\user\events;

use kilyakus\module\user\models\User;
use kilyakus\module\user\models\Account;
use yii\base\Event;

class ConnectEvent extends Event
{
    private $_user;
    private $_account;

    public function getAccount()
    {
        return $this->_account;
    }

    public function setAccount(Account $account)
    {
        $this->_account = $account;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser(User $user)
    {
        $this->_user = $user;
    }
}
