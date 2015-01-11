<?php

namespace artkost\qa\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Question Model Search
 * @package artkost\qa\models
 */
class QuestionSearch extends Question
{

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Question::find()->with('user');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (isset($params['tags']) && $params['tags']) {
            $query->andWhere(['like', 'tags', $params['tags']]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @param int $userID
     * @return ActiveDataProvider
     */
    public function searchFavorite($params, $userID)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query
            ->joinWith('favorites', true, 'RIGHT JOIN')
            ->where([Question::tableName() . '.user_id' => $userID]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @param $userID
     * @return ActiveDataProvider
     */
    public function searchMy($params, $userID)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query
            ->where(['user_id' => $userID]);

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