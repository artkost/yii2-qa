<?php

namespace artkost\qa\models;

class Answer extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

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