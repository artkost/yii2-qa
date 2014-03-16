<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/** @var ActiveForm $form */
$form = ActiveForm::begin(['id' => 'question-form']);

?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'title')->textInput(); ?>
<?= $form->field($model, 'content')->textarea(['rows' => 6]); ?>
<?= $form->field($model, 'tags')->textInput(['data-url' => Url::toRoute('tag-suggest')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<? ActiveForm::end(); ?>