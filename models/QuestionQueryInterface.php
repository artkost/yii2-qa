<?php
namespace artkost\qa\models;

use yii\db\ActiveRecordInterface;

interface QuestionQueryInterface extends ActiveRecordInterface
{
    /**
     * @return static
     */
    public function published();

    /**
     * @return static
     */
    public function draft();
}