<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var string $route
 */
use artkost\qa\Module;
use artkost\qa\models\Vote;
?>
<div class="vote">
    <?php if (Vote::isUserCan($model)): ?>
    <a class="vote-up" title="<?=Module::t('Vote up')?>" data-url="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'up']) ?>"><span
            class="glyphicon glyphicon-chevron-up"></span></a>
    <?php endif; ?>
    <span class="vote-count"><?= $model->votes ?></span>
    <?php if (Vote::isUserCan($model)): ?>
    <a class="vote-down" title="<?=Module::t('Vote down')?>" data-url="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'down']) ?>"><span
            class="glyphicon glyphicon-chevron-down"></span></a>
    <?php endif; ?>
</div>