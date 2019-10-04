<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace kilyakus\module\user\clients;
use yii\authclient\OAuth2;
class Instagram extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.instagram.com/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.instagram.com/oauth/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.instagram.com/v1';
    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $response = $this->api('users/self', 'GET');
        return $response['data'];
    }
    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        return $this->sendRequest($method, $url . '?access_token=' . $accessToken->getToken(), $params, $headers);
    }
    /**
     * @inheritdoc
     */
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
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Instagram';
    }
}