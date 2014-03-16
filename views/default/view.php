<?php

/**
 * @var \artkost\qa\models\Question $model
 */

use yii\helpers\Url;

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
$answers = $model->getRelation('answers')->all();
?>

<div class="qa-view container">
    <div class="row">
        <div class="col-lg-12">
            <div class="vote">
                <a class="vote-up" title="!" data-url="<?=Url::toRoute(['question-vote', 'id' => $model->id, 'vote' => 'up'])?>"><span class="glyphicon glyphicon-chevron-up"></span></a>
                <span class="vote-count"><?=$model->votes?></span>
                <a class="vote-down" title="!" data-url="<?=Url::toRoute(['question-vote', 'id' => $model->id, 'vote' => 'down'])?>"><span class="glyphicon glyphicon-chevron-down"></span></a>
            </div>
            <div class="question">
                <h1>
                    <?=$this->title?>
                    <?php if ($model->isAuthor()) :?> <a href="<?=Url::toRoute(['edit', 'id' => $model->id])?>" class="question-edit label label-default">Edit</a> <?php endif; ?>
                </h1>
                <div class="question-text">
                    <?= $model->content ?>
                </div>
                <div class="question-taglist">
                    <?php foreach ($model->tagsList as $tag): ?>
                    <a href="<?=Url::toRoute(['tagged', 'name' => $tag])?>" class="label label-default" rel="tag"><?=$tag?></a>
                    <?php endforeach; ?>
                </div>
                <div class="created">
                    <span title="2014-03-14 07:24:18Z" class="relativetime"><?=$model->updated_at ?></span>
                    <span class="user"><?=$model->user_id ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">

            <div class="answers-heading clearfix">
                <h3 class="answers-title"><?=Yii::t('artkost\qa', '{n, plural, =0{No Answers yet} =1{One Answer} other{# Answers}}', ['n' => count($answers)]);?></h3>
                <ul class="answers-tabs nav nav-tabs">
                    <li><a href="#"><?=Yii::t('artkost\qa', 'Active') ?></a></li>
                    <li><a href="#"><?=Yii::t('artkost\qa', 'Oldest') ?></a></li>
                    <li class="active"><a href="#"><?=Yii::t('artkost\qa', 'Votes') ?></a></li>
                </ul>
            </div>

            <div class="answers-list">
                <?php foreach($answers as $row): ?>
                    <div class="answer-row">
                        <div class="vote">
                            <a class="vote-up" title="!" data-url="<?=Url::toRoute(['answer-vote', 'id' => $row->id, 'vote' => 'up'])?>"><span class="glyphicon glyphicon-chevron-up"></span></a>
                            <span class="vote-count"><?= $row->votes?></span>
                            <a class="vote-down" title="!" data-url="<?=Url::toRoute(['answer-vote', 'id' => $row->id, 'vote' => 'down'])?>"><span class="glyphicon glyphicon-chevron-down"></span></a>
                        </div>
                        <div class="answer">
                            <div class="answer-text">
                                <?= $row->content ?>
                            </div>
                            <div class="created">
                                <span title="2014-03-14 07:24:18Z" class="relativetime"><?=$row->updated_at ?></span>
                                <span class="user"><?=$row->user_id ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <div class="row">
        <?= $this->render('_answer_form', ['model' => $answer, 'action' => Url::toRoute(['answer', 'id' => $model->id])]); ?>
    </div>
</div>