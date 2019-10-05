<?php
namespace kilyakus\module\user\widgets;

use yii\widgets\Menu;
use Yii;
use yii\base\Widget;

class UserMenu extends Widget
{
    public $items;
    
    public function init()
    {
        parent::init();
        
        $networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
        
        $this->items = [
                ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
                ['label' => Yii::t('user', 'Account'), 'url' => ['/user/settings/account']],
                [
                    'label' => Yii::t('user', 'Networks'),
                    'url' => ['/user/settings/networks'],
                    'visible' => $networksVisible
                ],
            ];
    }
    
    public function run()
    {
        return Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked',
            ],
            'items' => $this->items,
        ]);
    }
}
