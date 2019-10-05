<?php
namespace kilyakus\module\user;

use kilyakus\module\user\models\query\AccountQuery;
use kilyakus\module\user\models\Token;
use yii\authclient\ClientInterface;
use yii\base\BaseObject;
use yii\db\ActiveQuery;

class Finder extends BaseObject
{
    protected $userQuery;
    protected $tokenQuery;
    protected $accountQuery;
    protected $profileQuery;

    public function getUserQuery()
    {
        return $this->userQuery;
    }

    public function getTokenQuery()
    {
        return $this->tokenQuery;
    }

    public function getAccountQuery()
    {
        return $this->accountQuery;
    }

    public function getProfileQuery()
    {
        return $this->profileQuery;
    }

    public function setAccountQuery(ActiveQuery $accountQuery)
    {
        $this->accountQuery = $accountQuery;
    }

    public function setUserQuery(ActiveQuery $userQuery)
    {
        $this->userQuery = $userQuery;
    }

    public function setTokenQuery(ActiveQuery $tokenQuery)
    {
        $this->tokenQuery = $tokenQuery;
    }

    public function setProfileQuery(ActiveQuery $profileQuery)
    {
        $this->profileQuery = $profileQuery;
    }

    public function findUserById($id)
    {
        return $this->findUser(['id' => $id])->one();
    }

    public function findUserByUsername($username)
    {
        return $this->findUser(['username' => $username])->one();
    }

    public function findUserByEmail($email)
    {
        return $this->findUser(['email' => $email])->one();
    }

    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    public function findUser($condition)
    {
        return $this->userQuery->where($condition);
    }

    public function findAccount()
    {
        return $this->accountQuery;
    }

    public function findAccountById($id)
    {
        return $this->accountQuery->where(['id' => $id])->one();
    }

    public function findToken($condition)
    {
        return $this->tokenQuery->where($condition);
    }

    public function findTokenByParams($userId, $code, $type)
    {
        return $this->findToken([
            'user_id' => $userId,
            'code'    => $code,
            'type'    => $type,
        ])->one();
    }

    public function findProfileById($id)
    {
        return $this->findProfile(['user_id' => $id])->one();
    }

    public function findProfile($condition)
    {
        return $this->profileQuery->where($condition);
    }
}
