<?php
/**
 * @var \yii\db\ActiveRecord $model
 */
use artkost\qa\Module;
?>

<?php foreach ($model->tagsList as $tag): ?>
    <a href="<?= Module::url(['index', 'tags' => $tag]) ?>" class="label label-primary" title=""
       rel="tag"><?= $tag ?></a>
<?php endforeach; ?>