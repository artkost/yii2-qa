<?php
/**
 * @var \yii\db\ActiveRecord $model
 */
use yii\helpers\Html;

?>

<div class="qa-created">
    <div class="qa-user"><?= Html::encode($model->userName) ?></div>
    <div class="qa-time"><?= $model->updated ?></div>
</div>
