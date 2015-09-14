<?php

namespace artkost\qa\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecordInterface;
use yii\web\IdentityInterface;

/**
 * Answer Model Interface
 */
interface AnswerInterface extends ActiveRecordInterface
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    public static function removeRelation($questionID);

    public static function applyOrder(ActiveQuery $query, $order);

    /**
     * Checks if answer posted by author
     * @return boolean
     */
    public function isAuthor();

    /**
     * Checks if answer marked as correct
     * @return boolean
     */
    public function isCorrect();

    /**
     * Toggles correct answer status
     * @return boolean
     */
    public function toggleCorrect();

    /**
     * @return string
     */
    public function getUserName();

    /**
     * User relation
     * @return IdentityInterface
     */
    public function getUser();

    /**
     * Question relation
     * @return QuestionInterface
     */
    public function getQuestion();
}
