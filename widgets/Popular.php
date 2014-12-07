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

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models  = [];

        return $this->render('popular', [
            'models'  => $models,
        ]);
    }
}