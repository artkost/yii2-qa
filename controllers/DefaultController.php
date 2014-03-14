<?php

namespace artkost\qa\controllers;

use yii\web\Controller;
use artkost\qa\models\Answer;
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
        $this->render('index');
    }

    public function actionAsk()
    {
        $this->render('ask');
    }

    public function actionAnswer($id)
    {
        $model = Answer::find();
        if ($model->load($_POST)) {
            Yii::$app->session->setFlash('answerFormSubmitted');
            return $this->redirect([]);
        } else {
            return $this->render('answer', [
                'model' => $model,
            ]);
        }
    }
}