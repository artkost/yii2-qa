<?php
/**
 * @var \artkost\qa\models\Question $model
 * @var string $route
 */
use artkost\qa\Module;

?>
<div class="qa-favorite <?= $model->isFavorite() ? 'qa-favorite-active' : '' ?>">
    <a title="<?= Module::t('Add to favorite') ?>" class="qa-favorite-link"
       href="<?= Module::url(['question-favorite', 'id' => $model->id]) ?>">
        <span class="glyphicon <?= ($model->isFavorite()) ? 'glyphicon-star' : 'glyphicon-star-empty' ?>"></span>
    </a>
</div>