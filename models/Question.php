<?php

namespace artkost\qa\models;

class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }
}