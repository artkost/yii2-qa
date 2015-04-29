<?php
/**
 * @var \artkost\qa\models\Question $question
 * @var \artkost\qa\models\Answer $answer
 * @var string $route
 */
use artkost\qa\Module;

$userId = Yii::$app->user->id;

?>
<span class="qa-answer-correct js-answer-correct">
    <?php if ($question->isAuthor()): ?>
        <a title="<?= Module::t('main', 'Mark answer as correct') ?>" class="btn <?= ($answer->isCorrect()) ? 'btn-warning' : 'btn-default' ?> btn-sm qa-answer-correct-link js-answer-correct-link"
           href="<?= Module::url(['answer-correct', 'id' => $answer->id, 'questionId' => $question->id]) ?>">
            <span class="glyphicon glyphicon-ok"></span>
        </a>
    <?php else: ?>
        <?php if ($answer->isCorrect()): ?>
            <span title="<?= Module::t('main', 'Answer is correct') ?>" class="btn btn-warning btn-sm qa-answer-correct-button">
                <span class="glyphicon glyphicon-ok"></span>
            </span>
        <?php endif; ?>
    <?php endif; ?>
</span>
