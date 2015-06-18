<?php

namespace artkost\qa;

use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use artkost\qa\Module;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('qa') && ($module = $app->getModule('qa')) instanceof Module) {
            if ($app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'artkost\qa\commands';
            } else {
                if (!class_exists($app->get('user')->identityClass)) {
                    throw new InvalidConfigException('Yii::$app->user->identityClass does not exist');
                }
            }
        }

        $app->i18n->translations[Module::TRANSLATION . '*'] = [
            'class' => PhpMessageSource::className(),
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                Module::TRANSLATION . 'main' => 'main.php',
                Module::TRANSLATION . 'model' => 'model.php'
            ]
        ];

        Yii::$container->set('artkost\qa\models\AnswerInterface', Answer::className());
        Yii::$container->set('artkost\qa\models\QuestionInterface', Question::className());
    }
}
