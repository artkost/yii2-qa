<?php

namespace artkost\qa\models;

interface FavoriteInterface
{
    const CLASS_NAME = 'artkost\qa\models\FavoriteInterface';

    /**
     * @param $id
     * @return mixed
     */
    public function removeRelation($id);

    /**
     * @param $id
     * @return boolean
     */
    public function add($id);

    /**
     * @param $id
     * @return boolean
     */
    public function remove($id);

    /**
     * @return QuestionInterface
     */
    public function getQuestion();
}