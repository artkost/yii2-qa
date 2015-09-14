<?php

namespace artkost\qa\models;

use yii\db\ActiveRecordInterface;

interface FavoriteInterface extends ActiveRecordInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public static function removeRelation($id);

    /**
     * @param $id
     * @return boolean
     */
    public static function add($id);

    /**
     * @param $id
     * @return boolean
     */
    public static function remove($id);

    /**
     * @return QuestionInterface
     */
    public function getQuestion();
}