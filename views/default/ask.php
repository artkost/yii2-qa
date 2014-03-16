<?php

$this->title = 'Ask a Question';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1><?= $this->title ?></h1>
            <?= $this->render('_question_form', ['model' => $model]) ?>
        </div>
    </div>
</div>

