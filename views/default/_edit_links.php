<?php
use yii\helpers\Url;
/**
 * @var \artkost\qa\models\Question $model
 */
?>

<?php if ($model->isAuthor()) : ?>
    <a href="<?= Url::toRoute(['edit', 'id' => $model->id]) ?>"
       class="question-edit label label-success"><?= Yii::t('artkost\qa', 'Edit'); ?></a>
    <a href="<?= Url::toRoute(['delete', 'id' => $model->id]) ?>" class="question-delete label label-danger"
       data-confirm="<?= Yii::t('artkost\qa', 'Sure?'); ?>" data-method="post" data-pjax="0"><span
            class="glyphicon glyphicon-remove"></span></a>
<?php endif; ?>