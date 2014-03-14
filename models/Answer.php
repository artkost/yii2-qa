<?php

namespace artkost\qa\models;

class Answer extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }
}