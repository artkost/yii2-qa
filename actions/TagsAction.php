<?php

namespace artkost\qa\actions;

use artkost\qa\models\QuestionSearch;
use artkost\qa\models\Tag;
use artkost\qa\models\TagInterface;
use Yii;

class TagsAction extends Action
{

    public $viewFile = 'tags';

    /**
     * @param $tags
     * @return string
     */
    public function run($tags)
    {
        $tags = $this->getTagModel()->string2Array($tags);

        /** @var QuestionSearch $searchModel */
        $searchModel = $this->getModel();
        $dataProvider = $searchModel->search(['tags' => $tags]);
        $models = $dataProvider->getModels();

        return $this->render(compact('searchModel', 'models', 'dataProvider', 'tags'));
    }

    public function getTagModel()
    {
        return Yii::$container->get(TagInterface::CLASS_NAME);
    }

}
