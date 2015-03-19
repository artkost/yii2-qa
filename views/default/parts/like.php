<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var string $route
 */
use artkost\qa\models\Vote;
use artkost\qa\Module;

$userId = Yii::$app->user->id;

?>
<span class="qa-like js-vote">
    <?php if (Vote::isUserCan($model, $userId)): ?>
        <a class="btn btn-success btn-sm js-vote-up"
           href="<?= Module::url([$route, 'id' => $model->id, 'vote' => 'up']) ?>"
           title="<?= Module::t('main', 'Like') ?>">
            <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
        </a>
    <?php else: ?>
        <span class="btn btn-success btn-sm disabled js-vote-up" title="<?= Module::t('main', 'Like') ?>">
            <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
        </span>
    <?php endif; ?>
</span>