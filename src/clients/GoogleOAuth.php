<?php
namespace kilyakus\module\user\clients;

use yii\authclient\OAuth2;

/*
    Example application configuration:
    
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => 'google_client_id',
                    'clientSecret' => 'google_client_secret',
                ],
            ],
        ]
        ...
    ]
*/

class GoogleOAuth extends OAuth2
{
    public $authUrl = 'https://accounts.google.com/o/oauth2/auth';
    public $tokenUrl = 'https://accounts.google.com/o/oauth2/token';
    public $apiBaseUrl = 'https://www.googleapis.com/plus/v1';

    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'profile',
                'email',
            ]);
        }
    }

    protected function initUserAttributes()
    {
        return $this->api('people/me', 'GET');
    }

    protected function defaultName()
    {
        return 'google';
    }

    protected function defaultTitle()
    {
        return 'Google';
    }
}
