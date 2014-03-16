<?php
use yii\helpers\Url;

/**
 * @var \yii\db\ActiveRecord $model
 * @var string $route
 */

?>

<div class="vote">
    <a class="vote-up" title="!" data-url="<?= Url::toRoute([$route, 'id' => $model->id, 'vote' => 'up']) ?>"><span
            class="glyphicon glyphicon-chevron-up"></span></a>
    <span class="vote-count"><?= $model->votes ?></span>
    <a class="vote-down" title="!" data-url="<?= Url::toRoute([$route, 'id' => $model->id, 'vote' => 'down']) ?>"><span
            class="glyphicon glyphicon-chevron-down"></span></a>
</div>