<?php
namespace kilyakus\module\user\commands;

use kilyakus\module\user\Finder;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class DeleteController extends Controller
{
    protected $finder;

    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex($search)
    {
        if ($this->confirm(Yii::t('user', 'Are you sure? Deleted user can not be restored'))) {
            $user = $this->finder->findUserByUsernameOrEmail($search);
            if ($user === null) {
                $this->stdout(Yii::t('user', 'User is not found') . "\n", Console::FG_RED);
            } else {
                if ($user->delete()) {
                    $this->stdout(Yii::t('user', 'User has been deleted') . "\n", Console::FG_GREEN);
                } else {
                    $this->stdout(Yii::t('user', 'Error occurred while deleting user') . "\n", Console::FG_RED);
                }
            }
        }
    }
}
