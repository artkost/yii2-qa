<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var string $route
 */
use artkost\qa\Module;
?>
<div class="qa-favorite <?= ($model->isFavorite()) ? 'qa-favorite-active':''?>">
    <a class="qa-favorite-link" href="<?= Module::url(['question-favorite', 'id' => $model->id]) ?>">
        <span class="glyphicon <?= ($model->isFavorite()) ? 'glyphicon-star':'glyphicon-star-empty'?>"></span>
    </a>
</div>