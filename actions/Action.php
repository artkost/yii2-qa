<?php

namespace artkost\qa\actions;

use artkost\qa\ActiveRecord;
use artkost\qa\models\AnswerInterface;
use artkost\qa\models\QuestionInterface;
use artkost\qa\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class Action extends \yii\base\Action
{
    /**
     * @var string class name of the question model which will be handled by this action.
     * The model class must implement [[QuestionInterface]] or [[AnswerInterface]].
     * This property must be set.
     */
    public $modelClass;

    /**
     * @var callable a PHP callable that will be called to return the model corresponding
     * to the specified primary key value. If not set, [[findModel()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($class, $id, $action) {
     *     // $id is the primary key value. If composite primary key, the key values
     *     // will be separated by comma.
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return the model found, or throw an exception if not found.
     */
    public $findModel;

    /**
     * @var callable a PHP callable that will be called when running an action to determine
     * if the current user has the permission to execute the action. If not set, the access
     * check will not be performed. The signature of the callable should be as follows,
     *
     * ```php
     * function ($action, $model = null) {
     *     // $model is the requested model instance.
     *     // If null, it means no specific model (e.g. IndexAction)
     * }
     * ```
     */
    public $checkAccess;

    /**
     * @var string name of the view file
     */
    public $viewFile;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException(get_class($this) . '::$modelClass must be set.');
        }
    }

    /**
     * @param $id
     * @return ActiveRecord
     */
    protected function findModelByID($id)
    {
        return $this->findModel(get_class($this->getModel()), $id);
    }

    /**
     * @param string $modelClass
     * @param null $id
     * @return ActiveRecord
     */
    protected function findModel($modelClass, $id = null)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $modelClass, $id, $this);
        }

        $model = $modelClass::find()->where(['id' => $id])->one();

        return ($model !== null) ? $model : $this->notFoundException();
    }

    /**
     * @param array $params
     * @param array $config
     * @return QuestionInterface|AnswerInterface
     * @throws \yii\base\InvalidConfigException
     */
    protected function getModel($params = [], $config = [])
    {
        return Yii::$container->get($this->modelClass, $params, $config);
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    protected function getValue($key, $default)
    {
        if ($this->$key == null) {
            return $default;
        } else {
            return ArrayHelper::getValue($this, $key, $default);
        }
    }

    protected function render($params = [])
    {
        return $this->controller->render($this->viewFile, $params);
    }

    /**
     * @return null
     * @throws NotFoundHttpException
     */
    protected function notFoundException()
    {
        throw new NotFoundHttpException(Module::t('main', 'The requested page does not exist.'));
    }

    /**
     * @return null
     * @throws ForbiddenHttpException
     */
    protected function forbiddenException()
    {
        throw new ForbiddenHttpException(Module::t('main', 'You are not allowed to perform this action.'));
    }
}
