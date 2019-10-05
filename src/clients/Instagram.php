<?php
namespace kilyakus\module\user\clients;

use yii\authclient\OAuth2;

class Instagram extends OAuth2
{
    public $authUrl = 'https://api.instagram.com/oauth/authorize';
    public $tokenUrl = 'https://api.instagram.com/oauth/access_token';
    public $apiBaseUrl = 'https://api.instagram.com/v1';

    protected function initUserAttributes()
    {
        $response = $this->api('users/self', 'GET');
        return $response['data'];
    }

    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        return $this->sendRequest($method, $url . '?access_token=' . $accessToken->getToken(), $params, $headers);
    }

    protected function defaultName()
    {
        return 'instagram';
    }

    public function getUsername()
    {
        return isset($this->getUserAttributes()['username'])
            ? $this->getUserAttributes()['username']
            : null;
    }

    protected function defaultTitle()
    {
        return 'Instagram';
    }
}