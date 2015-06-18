<?php

namespace artkost\qa\actions;

use artkost\qa\models\QuestionSearch;
use Yii;

class MyListAction extends Action
{
    public $viewFile = 'index';

    /**
     * @return string
     */
    public function run()
    {
        /** @var QuestionSearch $searchModel */
        $searchModel = $this->getModel();
        $dataProvider = $searchModel->searchMy(Yii::$app->request->getQueryParams(), Yii::$app->user->id);
        $models = $dataProvider->getModels();

        return $this->render(compact('searchModel', 'models', 'dataProvider'));
    }

}
