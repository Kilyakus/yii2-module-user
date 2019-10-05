<?php
namespace kilyakus\module\user\events;

use kilyakus\module\user\models\Account;
use yii\authclient\ClientInterface;
use yii\base\Event;

class AuthEvent extends Event
{
    private $_client;
    private $_account;

    public function getAccount()
    {
        return $this->_account;
    }

    public function setAccount(Account $account)
    {
        $this->_account = $account;
    }

    public function getClient()
    {
        return $this->_client;
    }

    public function setClient(ClientInterface $client)
    {
        $this->_client = $client;
    }
}
