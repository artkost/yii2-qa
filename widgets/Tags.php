<?php

namespace artkost\qa\widgets;

use artkost\qa\models\Tag;
use yii\base\Widget;

/**
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Tags extends Widget
{

    public $limit = 10;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models = Tag::find()
            ->orderBy('frequency')
            ->limit($this->limit)
            ->all();

        return $this->render('tags', [
            'models'  => $models,
        ]);
    }
}
