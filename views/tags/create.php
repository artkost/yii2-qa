<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model artkost\qa\models\Tag */

$this->title = Yii::t('artkost\qa', 'Create {modelClass}', [
    'modelClass' => 'Tag',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('artkost\qa', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
