<?php
/**
 * @var \artkost\qa\models\Question $model
 * @var \yii\data\ActiveDataProvider $answerDataProvider
 * @var string $answerOrder
 * @var \artkost\qa\models\Answer $answer
 * @var \yii\web\View $this
 */

use artkost\qa\Module;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$answerOrders = [
    'Active' => 'active',
    'Oldest' => 'oldest',
    'Votes' => 'votes'
];

?>

<div class="qa-view container">
    <div class="row">
        <div class="col-lg-12">

            <?= $this->render('_vote', ['model' => $model, 'route' => 'question-vote']) ?>

            <div class="question">
                <h1>
                    <?= $this->title ?>
                    <?= $this->render('_edit_links', ['model' => $model]) ?>
                </h1>

                <div class="question-text">
                    <?= $model->content ?>
                </div>
                <div class="question-taglist">
                    <?= $this->render('_tags', ['model' => $model]) ?>
                </div>
                <?= $this->render('_created', ['model' => $model]) ?>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">

            <div class="answers-heading clearfix">
                <h3 class="answers-title"><?= Module::t('{n, plural, =0{No Answers yet} =1{One Answer} other{# Answers}}', ['n' => $answerDataProvider->totalCount]); ?></h3>
                <ul class="answers-tabs nav nav-tabs">
                    <?php foreach ($answerOrders as $aId => $aOrder): ?>
                        <li <?= ($aOrder == $answerOrder) ? 'class="active"' : '' ?> >
                            <a href="<?= Module::url(['view', 'id' => $model->id, 'alias' => $model->alias, 'answers' => $aOrder]) ?>"><?= Module::t($aId) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="answers-list">
                <?php foreach ($answerDataProvider->models as $row): ?>
                    <div class="answer-row">

                        <?= $this->render('_vote', ['model' => $row, 'route' => 'answer-vote']) ?>

                        <div class="answer clearfix">
                            <div class="answer-text">
                                <?= $row->content ?>
                            </div>
                            <?= $this->render('_created', ['model' => $row]) ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="answers-pager">
                <?= $this->render('_pager', ['dataProvider' => $answerDataProvider]) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <?= $this->render('_answer_form', ['model' => $answer, 'action' => Module::url(['answer', 'id' => $model->id])]); ?>
    </div>
</div>