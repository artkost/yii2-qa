<?php
/**
 * @var \artkost\qa\models\Question $model
 * @var \yii\data\ActiveDataProvider $answerDataProvider
 * @var string $answerOrder
 * @var \artkost\qa\models\Answer $answer
 * @var \yii\web\View $this
 */

use artkost\qa\Module;
use yii\helpers\Html;

$this->title = Html::encode($model->title);
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$answerOrders = [
    'Active' => 'active',
    'Oldest' => 'oldest',
    'Votes' => 'votes'
];

?>
<section class="qa-view">
    <article class="qa-view-question row">
        <header class="page-header col-md-12">
            <h1 class="qa-view-title">
                <?= Html::encode($this->title) ?>

                <?php if ($model->isDraft()): ?>
                    <small><span class="label label-default"><?= Module::t('main', 'Draft') ?></span></small>
                <?php endif; ?>
            </h1>
        </header>
        <section class="qa-view-aside col-md-2" role="aside">
            <?= $this->render('parts/created', ['model' => $model]) ?>
            <div class="qa-view-actions">
                <?= $this->render('parts/vote', ['model' => $model, 'route' => 'question-vote']) ?>
                <?= $this->render('parts/favorite', ['model' => $model]) ?>
            </div>
        </section>
        <section class="qa-view-body col-md-10" role="main">
            <div class="panel panel-default">
                <section class="panel-body qa-view-text">
                    <?= $model->body ?>
                </section>
                <footer class="panel-footer">
                    <div class="qa-view-meta">
                        <?= $this->render('parts/edit-links', ['model' => $model]) ?>
                        <?= $this->render('parts/tags-list', ['model' => $model]) ?>
                    </div>
                </footer>
            </div>
        </section>
    </article>

    <div class="qa-view-answers row">
        <div class="qa-view-answers-heading col-md-12">
            <?php if ($answerDataProvider->totalCount): ?>
                <ul class="qa-view-tabs nav nav-pills">
                    <?php foreach ($answerOrders as $aId => $aOrder): ?>
                        <li <?= ($aOrder == $answerOrder) ? 'class="active"' : '' ?> >
                            <a href="<?= Module::url(['view', 'id' => $model->id, 'alias' => $model->alias, 'answers' => $aOrder]) ?>">
                                <?= Module::t('main', $aId) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <h3 class="qa-view-title"><?= Module::t('main', '{n, plural, =0{No answers yet} =1{One answer} other{# answers}}', ['n' => $answerDataProvider->totalCount]); ?></h3>
        </div>

        <div class="qa-view-answers-list col-md-12">
        <?php foreach ($answerDataProvider->models as $row /** @var \artkost\qa\models\Answer $row */): ?>
            <article class="qa-view-answer row">
                <section class="qa-view-answer-aside col-md-2">
                    <?= $this->render('parts/created', ['model' => $row]) ?>
                    <div class="qa-answer-like">
                        <?= $this->render('parts/answer-correct', ['answer' => $row, 'question' => $model]) ?>
                        <?= $this->render('parts/like', ['model' => $row, 'route' => 'answer-vote']) ?>
                    </div>
                    <?= $this->render('parts/edit-links', ['model' => $row]) ?>
                </section>
                <section class="panel <?= ($row->isCorrect()) ? 'panel-warning': 'panel-default' ?> col-md-10">
                    <section class="panel-body">
                        <div class="qa-view-text">
                            <?= $row->body ?>
                        </div>
                    </section>
                </section>
            </article>

        <?php endforeach; ?>
        </div>

        <div class="qa-view-answer-pager">
            <?= $this->render('parts/pager', ['dataProvider' => $answerDataProvider]) ?>
        </div>

        <div class="qa-view-answer-form">
            <?= $this->render('parts/form-answer', ['model' => $answer, 'action' => Module::url(['answer', 'id' => $model->id])]); ?>
        </div>
    </div>
</section>
