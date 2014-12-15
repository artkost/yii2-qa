<?php

namespace artkost\qa;

use artkost\qa\models\Question;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidCallException;
use yii\helpers\Url;
use yii\web\GroupUrlRule;
use yii\web\IdentityInterface;

/**
 * This is the main module class for the QA module.
 *
 * To use QA, include it as a module in the application configuration like the following:
 *
 * ~~~
 * return [
 *     ......
 *     'modules' => [
 *         'qa' => ['class' => 'artkost\qa\Module'],
 *     ],
 * ]
 * ~~~
 *
 * With the above configuration, you will be able to access QA Module in your browser using
 * the URL `http://localhost/path/to/index.php?r=qa`
 *
 * If your application enables [[UrlManager::enablePrettyUrl|pretty URLs]] and you have defined
 * custom URL rules or enabled [[UrlManager::enableStrictParsing], you may need to add
 * the following URL rules at the beginning of your URL rule set in your application configuration
 * in order to access QA:
 *
 * ~~~
 * 'rules' => [
 *     'qa' => 'qa',
 *     'qa/<controller>' => 'qa/<controller>',
 *     'qa/<controller>/<action>' => 'qa/<controller>/<action>',
 *     ...
 * ],
 * ~~~
 *
 * You can then access QA via URL: `http://localhost/path/to/index.php/qa`
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    protected $_mail;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = '\artkost\qa\controllers';

    /**
     * Allow users to add tags
     * @var bool
     */
    public $allowUserGeneratedTags = false;

    /**
     * Formatter function name in user model, or callable
     * @var string|callable
     */
    public $userNameFormatter = 'getId';

    /**
     * @var string The prefix for user module URL.
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'qa';

    /**
     * @var array The rules to be used in URL management.
     */
    public $urlRules = [
        'ask' => 'default/ask',
        'my' => 'default/my',
        'favorite' => 'default/favorite',
        '' => 'default/index',
        'tag/<tags>' => 'default/tags',
        'tag-suggest' => 'default/tag-suggest',
        '<alias>-<id>' => 'default/view'
    ];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = '\artkost\qa\commands';
        } else if ($app instanceof \yii\web\Application) {
            $configUrlRule = [
                'routePrefix' => $this->id,
                'prefix' => $this->urlPrefix,
                'rules' => $this->urlRules
            ];

            $app->get('urlManager')->rules[] = new GroupUrlRule($configUrlRule);
        }

        $app->get('i18n')->translations['artkost/qa/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                'qa/main' => 'main.php',
                'qa/model' => 'main.php'
            ]
        ];
    }



    /**
     * Alias function for [[Yii::t()]]
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('artkost/qa/' . $category, $message, $params, $language);
    }

    /**
     * Alias function for [[Url::toRoute()]]
     * @param $route
     * @param bool $scheme
     * @return string
     */
    public static function url($route, $scheme = false)
    {
        return Url::toRoute($route, $scheme);
    }

    /**
     * @return \yii\swiftmailer\Mailer Mailer instance with predefined templates.
     */
    public function getMail()
    {
        if ($this->_mail === null) {
            $this->_mail = Yii::$app->getMailer();
            $this->_mail->htmlLayout = Yii::getAlias($this->id . '/mails/layouts/html');
            $this->_mail->textLayout = Yii::getAlias($this->id . '/mails/layouts/text');
            $this->_mail->viewPath = Yii::getAlias($this->id . '/mails/views');

            if (isset(Yii::$app->params['robotEmail']) && Yii::$app->params['robotEmail'] !== null) {
                $this->_mail->messageConfig['from'] = !isset(Yii::$app->params['robotName']) ? Yii::$app->params['robotEmail'] : [Yii::$app->params['robotEmail'] => Yii::$app->params['robotName']];
            }
        }
        return $this->_mail;
    }

    /**
     * @param \app\models\User $model
     * @return string
     * @throws InvalidCallException
     */
    public function getUserName($model)
    {
        if (is_callable($this->userNameFormatter)) {
            return call_user_func($this->userNameFormatter, $model);
        } else if (method_exists($model, $this->userNameFormatter)) {
            return call_user_func([$model, $this->userNameFormatter]);
        } else throw new InvalidCallException('Invalid userNameFormatter function');
    }

}