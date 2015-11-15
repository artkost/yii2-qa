<?php
namespace artkost\qa\models;

interface QuestionQueryInterface
{
    const CLASS_NAME = 'artkost\qa\models\QuestionQueryInterface';

    /**
     * @return static
     */
    public function published();

    /**
     * @return static
     */
    public function draft();
}