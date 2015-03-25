<?php
/**
 * @var \artkost\qa\models\Answer $model
 * @var \artkost\qa\models\Question $question
 */

use artkost\qa\Module;

$this->title = Module::t('main', 'Answer to {title}', ['title' => $question->title]);
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $question->title, 'url' => ['view', 'id' => $question->id, 'alias' => $question->alias]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-lg-12">
        <h1><?= $this->title ?></h1>
        <div class="qa-view-answer-form">
            <?= $this->render('parts/form-answer', ['model' => $model, 'action' => Module::url(['answer', 'id' => $model->id])]); ?>
        </div>
    </div>
</div>

