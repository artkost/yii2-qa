<?php

namespace artkost\qa\models;

use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecordInterface;
use yii\web\IdentityInterface;

/**
 * Answer Model Interface
 */
interface AnswerInterface extends ActiveRecordInterface
{
    const CLASS_NAME = 'artkost\qa\models\AnswerInterface';
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * Removes answers related to question
     * @param $questionID
     * @return mixed
     */
    public function removeRelation($questionID);

    /**
     * @param ActiveQueryInterface $query
     * @param $order
     * @return mixed
     */
    public function applyOrder(ActiveQueryInterface $query, $order);

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
