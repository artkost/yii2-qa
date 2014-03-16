<?php
use yii\helpers\Url;

/**
 * @var \yii\db\ActiveRecord $model
 */
?>

<?php foreach ($model->tagsList as $tag): ?>
    <a href="<?= Url::toRoute(['index', 'tags' => $tag]) ?>" class="label label-primary" title=""
       rel="tag"><?= $tag ?></a>
<?php endforeach; ?>