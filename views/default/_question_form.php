<?php

use yii\widgets\ActiveForm;


/** @var ActiveForm $form */
$form = ActiveForm::widget(['id' => 'question-form']);
?>

<?= $form->field($model, 'title')->textInput(); ?>
<?= $form->field($model, 'content')->textarea(['rows' => 6]); ?>
<?= $form->field($model, 'tags')->textInput() ?>

<? ActiveForm::end(); ?>