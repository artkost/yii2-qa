<?php

namespace artkost\qa\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = 'main';
    /**
     * @var \yii\gii\Module
     */
    public $module;

    public function actionIndex()
    {
        $this->render('index');
    }
}