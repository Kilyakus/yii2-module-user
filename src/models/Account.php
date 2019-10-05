<?php
namespace kilyakus\module\user\models;

use kilyakus\module\user\clients\ClientInterface;
use kilyakus\module\user\Finder;
use kilyakus\module\user\models\query\AccountQuery;
use kilyakus\module\user\traits\ModuleTrait;
use yii\authclient\ClientInterface as BaseClientInterface;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;

class Account extends ActiveRecord
{
    use ModuleTrait;

    protected static $finder;

    private $_data;

    public static function tableName()
    {
        return '{{%social_account}}';
    }

    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    public function getIsConnected()
    {
        return $this->user_id != null;
    }

    public function getDecodedData()
    {
        if ($this->_data == null) {
            $this->_data = Json::decode($this->data);
        }

        return $this->_data;
    }

    public function getConnectUrl()
    {
        $code = \Yii::$app->security->generateRandomString();
        $this->updateAttributes(['code' => md5($code)]);

        return Url::to(['/user/registration/connect', 'code' => $code]);
    }

    public function connect(User $user)
    {
        return $this->updateAttributes([
            'username' => null,
            'email'    => null,
            'code'     => null,
            'user_id'  => $user->id,
        ]);
    }

    public static function find()
    {
        return \Yii::createObject(AccountQuery::className(), [get_called_class()]);
    }

    public static function create(BaseClientInterface $client)
    {
        $account = \Yii::createObject([
            'class'      => static::className(),
            'provider'   => $client->getId(),
            'client_id'  => $client->getUserAttributes()['id'],
            'data'       => Json::encode($client->getUserAttributes()),
        ]);

        if ($client instanceof ClientInterface) {
            $account->setAttributes([
                'username' => $client->getUsername(),
                'email'    => $client->getEmail(),
            ], false);
        }

        if (($user = static::fetchUser($account)) instanceof User) {
            $account->user_id = $user->id;
        }

        $account->save(false);

        return $account;
    }

    public static function connectWithUser(BaseClientInterface $client)
    {
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->session->setFlash('danger', \Yii::t('user', 'Something went wrong'));

            return;
        }

        $account = static::fetchAccount($client);

        if ($account->user === null) {
            $account->link('user', \Yii::$app->user->identity);
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account has been connected'));
        } else {
            \Yii::$app->session->setFlash(
                'danger',
                \Yii::t('user', 'This account has already been connected to another user')
            );
        }
    }

    protected static function fetchAccount(BaseClientInterface $client)
    {
        $account = static::getFinder()->findAccount()->byClient($client)->one();

        if (null === $account) {
            $account = \Yii::createObject([
                'class'      => static::className(),
                'provider'   => $client->getId(),
                'client_id'  => $client->getUserAttributes()['id'],
                'data'       => Json::encode($client->getUserAttributes()),
            ]);
            $account->save(false);
        }

        return $account;
    }

    protected static function fetchUser(Account $account)
    {
        $user = static::getFinder()->findUserByEmail($account->email);

        if (null !== $user) {
            return $user;
        }

        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'connect',
            'username' => $account->username,
            'email'    => $account->email,
        ]);

        if (!$user->validate(['email'])) {
            $account->email = null;
        }

        if (!$user->validate(['username'])) {
            $account->username = null;
        }

        return $user->create() ? $user : false;
    }

    protected static function getFinder()
    {
        if (static::$finder === null) {
            static::$finder = \Yii::$container->get(Finder::className());
        }

        return static::$finder;
    }
}
