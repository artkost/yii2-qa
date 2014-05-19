<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var string $route
 */
use artkost\qa\Module;
use artkost\qa\models\Vote;
$userId = Yii::$app->user->id;
?>
<div class="qa-vote">
    <?php if (Vote::isUserCan($model, $userId)): ?>
    <a class="qa-vote-up"
       href="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'up']) ?>"
       title="<?= Module::t('Vote up') ?>">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <?php endif; ?>
    <span class="qa-vote-count"><?= $model->votes ?></span>
    <?php if (Vote::isUserCan($model, $userId)): ?>
    <a class="qa-vote-down"
       href="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'down']) ?>"
       title="<?= Module::t('Vote down') ?>">
        <span class="glyphicon glyphicon-chevron-down"></span>
    </a>
    <?php endif; ?>
</div>