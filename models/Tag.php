<?php

namespace artkost\qa\models;

class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }
}