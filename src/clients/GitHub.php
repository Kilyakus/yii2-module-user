<?php
namespace kilyakus\module\user\clients;

use yii\authclient\clients\GitHub as BaseGitHub;

class GitHub extends BaseGitHub implements ClientInterface
{
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email'])
            ? $this->getUserAttributes()['email']
            : null;
    }
    
    public function getUsername()
    {
        return isset($this->getUserAttributes()['login'])
            ? $this->getUserAttributes()['login']
            : null;
    }
}
