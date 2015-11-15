<?php

namespace artkost\qa;

use artkost\qa\models\Answer;
use artkost\qa\models\AnswerInterface;
use artkost\qa\models\Favorite;
use artkost\qa\models\FavoriteInterface;
use artkost\qa\models\Question;
use artkost\qa\models\QuestionInterface;
use artkost\qa\models\QuestionQuery;
use artkost\qa\models\QuestionQueryInterface;
use artkost\qa\models\QuestionSearch;
use artkost\qa\models\QuestionSearchInterface;
use artkost\qa\models\Tag;
use artkost\qa\models\TagInterface;
use artkost\qa\models\Vote;
use artkost\qa\models\VoteInterface;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use artkost\qa\Module as QaModule;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('qa') && ($module = $app->getModule('qa')) instanceof QaModule) {
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

        Yii::$container->set(AnswerInterface::CLASS_NAME, Answer::className());
        Yii::$container->set(QuestionInterface::CLASS_NAME, Question::className());
        Yii::$container->set(QuestionQueryInterface::CLASS_NAME, QuestionQuery::className());
        Yii::$container->set(QuestionSearchInterface::CLASS_NAME, QuestionSearch::className());
        Yii::$container->set(TagInterface::CLASS_NAME, Tag::className());
        Yii::$container->set(FavoriteInterface::CLASS_NAME, Favorite::className());
        Yii::$container->set(VoteInterface::CLASS_NAME, Vote::className());
    }
}
