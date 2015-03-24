<?php
/**
 * @var \artkost\qa\models\Answer $model
 */

use artkost\qa\Module;

?>
<div class="qa-view-answer-form">
    <?= $this->render('parts/form-answer', ['model' => $model, 'action' => Module::url(['answer', 'id' => $model->id])]); ?>
</div>
