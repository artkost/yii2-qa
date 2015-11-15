<?php

namespace artkost\qa\models;

use yii\db\ActiveRecordInterface;

interface QuestionInterface extends ActiveRecordInterface
{
    const CLASS_NAME = 'artkost\qa\models\QuestionInterface';

    const STATUS_DRAFT = 0;

    const STATUS_PUBLISHED = 1;

    public function incrementAnswers($id);

    public function decrementAnswers($id);

    public function normalizeTags($attribute, $params);

    public function haveDraft($data);

    public function isAuthor();

    public function isFavorite($user = false);

    public function isDraft();

    public function isUserUnique();

    public function toggleFavorite();

    public function getTagsList();

    public function getUserName();

    public function getAnswers();

    public function getUser();

    public function getFavorite();

    public function getFavorites();
}
