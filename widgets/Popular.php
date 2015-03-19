<?php

namespace artkost\qa\widgets;

use artkost\qa\models\Question;
use yii\base\Widget;

/**
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Popular extends Widget
{
    public $limit = 10;
    public $views = 10;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models = Question::find()
            ->where('views > :views', [':views' => $this->views])
            ->limit($this->limit)
            ->all();

        return $this->render('popular', [
            'models' => $models,
        ]);
    }
}