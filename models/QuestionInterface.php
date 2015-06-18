<?php

namespace artkost\qa\models;

interface QuestionInterface
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    public static function incrementAnswers($id);

    public static function decrementAnswers($id);

    public function normalizeTags($attribute, $params);

    public function isAuthor();

    public function isFavorite($user = false);

    public function haveDraft($data);

    public function isDraft();

    public function isUserUnique();

    public function toggleFavorite();

    public function getTagsList();

    public function getUserName();

    public function getAnswers();

    public function getUser();

    public function getFavorite();

    public function getFavorites();

    public function getFavoriteCount();
}
