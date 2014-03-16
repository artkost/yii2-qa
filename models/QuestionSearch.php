<?php

namespace artkost\qa\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Question Model Search
 * @package artkost\qa\models
 */
class QuestionSearch extends Model
{
    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Question::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (isset($params['tags']) && $params['tags']) {
            $tags = Tag::string2Array($params['tags']);

            $query->andWhere(['like', 'tags', $tags]);
        }

        if (!($this->load($params, '') && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        $value = $this->$modelAttribute;
        if (trim($value) === '') {
            return;
        }
        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}