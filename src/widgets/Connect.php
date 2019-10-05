<?php
namespace kilyakus\module\user\widgets;

use Yii;
use yii\authclient\ClientInterface;
use yii\authclient\widgets\AuthChoice;
use yii\authclient\widgets\AuthChoiceAsset;
use yii\helpers\Html;
use yii\helpers\Url;

class Connect extends AuthChoice
{
    public $accounts;
    public $options = [];

    public function init()
    {
        AuthChoiceAsset::register(Yii::$app->view);
        if ($this->popupMode) {
            Yii::$app->view->registerJs("\$('#" . $this->getId() . "').authchoice();");
        }
        $this->options['id'] = $this->getId();
        echo Html::beginTag('div', $this->options);
    }

    public function createClientUrl($provider)
    {
        if ($this->isConnected($provider)) {
            return Url::to(['/user/settings/disconnect', 'id' => $this->accounts[$provider->getId()]->id]);
        } else {
            return parent::createClientUrl($provider);
        }
    }

    public function isConnected(ClientInterface $provider)
    {
        return $this->accounts != null && isset($this->accounts[$provider->getId()]);
    }
}
