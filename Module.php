<?php

namespace artkost\qa;

use Yii;
use yii\base\InvalidCallException;
use yii\helpers\Url;

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
 *      'qa/ask' => 'default/ask',
 *      'qa/my' => 'default/my',
 *      'qa/favorite' => 'default/favorite',
 *      'qa' => 'default/index',
 *      'qa/tag/<tags>' => 'default/tags',
 *      'qa/tag-suggest' => 'default/tag-suggest',
 *      'qa/<alias>-<id>' => 'default/view'
 *     ...
 * ],
 * ~~~
 *
 * You can then access QA via URL: `http://localhost/path/to/index.php/qa`
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Module extends \yii\base\Module
{
    /**
     * Formatter function name in user model, or callable
     * @var string|callable
     */
    public $userNameFormatter = 'getId';

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
