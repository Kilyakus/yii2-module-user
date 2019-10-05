<?php
namespace kilyakus\module\user\models\query;

use kilyakus\module\user\models\Account;
use yii\authclient\ClientInterface;
use yii\db\ActiveQuery;

class AccountQuery extends ActiveQuery
{
    public function byCode($code)
    {
        return $this->andWhere(['code' => md5($code)]);
    }

    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
    }

    public function byUser($userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    public function byClient(ClientInterface $client)
    {
        return $this->andWhere([
            'provider'  => $client->getId(),
            'client_id' => $client->getUserAttributes()['id'],
        ]);
    }
}
