<?php

namespace artkost\qa\models;

use yii\db\ActiveQuery;

/**
 * Answer Model Interface
 */
interface AnswerInterface
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    public static function removeRelation($questionID);

    public static function applyOrder(ActiveQuery $query, $order);

    public function isAuthor();

    public function isCorrect();

    public function toggleCorrect();

    public function getUser();

    public function getUserName();

    public function getQuestion();
}
