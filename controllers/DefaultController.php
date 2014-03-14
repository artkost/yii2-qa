<?php

namespace artkost\qa\controllers;

use artkost\qa\models\Question;
use artkost\qa\models\Answer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class DefaultController extends Controller
{
    public $layout = 'main';
    /**
     * @var \artkost\qa\Module
     */
    public $module;

    public function actionIndex()
    {
        $models = $this->findQuestionModel()->all();

        $this->render('index', compact('models'));
    }

    public function actionView($id)
    {
        $model = $this->findQuestionModel($id);

        $this->render('view', compact('model'));
    }

    public function actionAsk()
    {
        $model = new Question();
        if ($model->load($_POST)) {
            Yii::$app->session->setFlash('questionFormSubmitted');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('ask', compact('model'));
        }
    }

    public function actionAnswer($id)
    {
        $model = new Answer();
        if ($model->load($_POST)) {
            Yii::$app->session->setFlash('answerFormSubmitted');
            return $this->redirect(['view', 'id' => $id]);
        } else {
            return $this->render('answer', compact('model'));
        }
    }

    /**
     * @param null $id
     * @return null|\yii\db\ActiveQuery|static
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findAnswerModel($id = null)
    {
        /** if $id is null, return ActiveQuery */
        if (($model = Answer::find($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $id
     * @return null|\yii\db\ActiveQuery|static
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findQuestionModel($id = null)
    {
        /** if $id is null, return ActiveQuery */
        if (($model = Answer::find($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}