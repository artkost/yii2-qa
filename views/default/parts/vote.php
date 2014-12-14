<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var string $route
 */
use artkost\qa\models\Vote;
use artkost\qa\Module;

$userId = Yii::$app->user->id;
?>
<div class="qa-vote">
    <?php if (Vote::isUserCan($model, $userId)): ?>
        <a class="btn btn-default qa-vote-up"
           href="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'up']) ?>"
           title="<?= Module::t('main', 'Vote up') ?>">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </a>
    <?php else: ?>
        <span class="btn btn-default disabled qa-vote-up" title="<?= Module::t('main', 'Vote up') ?>">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </span>
    <?php endif; ?>
    <span class="qa-vote-count"><?= $model->votes ?></span>
    <?php if (Vote::isUserCan($model, $userId)): ?>
        <a class="btn btn-default qa-vote-down"
           href="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'down']) ?>"
           title="<?= Module::t('main', 'Vote down') ?>">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </a>
    <?php else: ?>
        <span class="btn btn-default disabled qa-vote-down"
              title="<?= Module::t('main', 'Vote down') ?>">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </span>
    <?php endif; ?>
</div>