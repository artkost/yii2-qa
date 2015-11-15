<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var string $route
 * @var ActiveRecord $model
 * @var Vote $vote
 */

use artkost\qa\ActiveRecord;
use artkost\qa\models\Vote;
use artkost\qa\Module;

$userId = Yii::$app->user->id;
?>
<div class="qa-vote js-vote">
    <?php if ($vote->isUserCan($model, $userId)): ?>
        <a class="qa-vote-up js-vote-up"
           href="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'up']) ?>"
           title="<?= Module::t('main', 'Vote up') ?>">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </a>
    <?php else: ?>
        <span class="qa-vote-up qa-vote-up-disabled js-vote-up" title="<?= Module::t('main', 'Vote up') ?>">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </span>
    <?php endif; ?>
    <span class="qa-vote-count"><?= $model->votes ?></span>
    <?php if ($vote->isUserCan($model, $userId)): ?>
        <a class="qa-vote-down js-vote-down"
           href="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'down']) ?>"
           title="<?= Module::t('main', 'Vote down') ?>">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </a>
    <?php else: ?>
        <span class="qa-vote-down qa-vote-down-disabled js-vote-down"
              title="<?= Module::t('main', 'Vote down') ?>">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </span>
    <?php endif; ?>
</div>
