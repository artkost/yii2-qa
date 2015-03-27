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
     * Translation category for module
     */
    const TRANSLATION = 'artkost/qa/';

    /**
     * Formatter function for name in user model, or callable
     * @var string|callable
     */
    public $userNameFormatter = 'getId';

    /**
     * Formatter function for date in answer and question models, or callable
     * @var string|callable
     */
    public $dateFormatter;

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
        return Yii::t(self::TRANSLATION . $category, $message, $params, $language);
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
     * @param string $attribute
     * @return string
     * @throws InvalidCallException
     */
    public function getUserName($model, $attribute)
    {
        return $this->callConfigFunction($model, 'userNameFormatter', $attribute, function($modelInstance) {
            return $modelInstance->id;
        });
    }

    /**
     * @param $model
     * @param string $attribute
     * @return string
     */
    public function getDate($model, $attribute)
    {
        return $this->callConfigFunction($model, 'dateFormatter', $attribute, function($modelInstance) use($attribute) {
            return Yii::$app->formatter->asTime($modelInstance->{$attribute});
        });
    }

    /**
     * @param \app\models\User $model
     * @param string $functionName
     * @param string $attribute
     * @param \Closure $defaultFunction
     * @return string
     * @throws InvalidCallException
     */
    protected function callConfigFunction($model, $functionName, $attribute, $defaultFunction = null)
    {
        if (is_callable($this->{$functionName})) {
            return call_user_func($this->{$functionName}, $model, $attribute);
        } else if (method_exists($model, $this->{$functionName})) {
            return call_user_func([$model, $this->{$functionName}], $model, $attribute);
        } else if ($defaultFunction instanceof \Closure) {
            return $defaultFunction($model, $attribute);
        } else throw new InvalidCallException("Invalid {$functionName} function");
    }
}
