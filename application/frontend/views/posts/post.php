<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$postsPath = '/posts';
?>
<h3><?= $post->title ?></h3>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6"><h3 class="panel-title"><a href="
                                <?= "$post->id"; ?>
                            ">Title:
                        <?= $post->title; ?>
                    </a></h3>
            </div>
            <div class="col-md-6"><h3 class="panel-title text-right">From:
                    <?= $post->created_at; ?>
                </h3></div>
        </div>
    </div>
    <div class="panel-body">
        <p>
            <?= $post->post; ?>
        </p>
    </div>
    <div class="panel-footer">
        <?php if (Yii::$app->user->identity->id == $post->user_id) : ?>
            <div class="row">
                <div class="col-md-1">
                    <?php $form = ActiveForm::begin(['id' => 'comment-form', 'action' => "$postsPath/$post->id/change",
                        'method' => 'GET']); ?>
                    <div class="form-group">
                        <?= Html::submitButton('Change', ['class' => 'btn btn-success', 'name' => 'comment-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-1">
                    <?php $form = ActiveForm::begin(['id' => 'comment-form', 'action' => "$postsPath/$post->id",
                        'method' => 'DELETE']); ?>
                    <div class="form-group">
                        <?= Html::submitButton('Delete', ['class' => 'btn btn-danger', 'name' => 'comment-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php else : ?>
            Author:
            <?= $post->user->name; ?>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(['id' => 'comment-form', 'action' => '/comments/create']); ?>
        <?php if (Yii::$app->user->isGuest) : ?>
            <div class="form-group">
                <?= $form->field($user, 'name')->textInput(['autofocus' => true]) ?>
            </div>
            <div class="form-group">
                <?= $form->field($user, 'email') ?>
            </div>
        <?php else : ?>
            <div class="form-group hidden">
                <?= $form->field($comment, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->id]) ?>
            </div>
        <?php endif; ?>
        <div class="form-group hidden">
            <?= $form->field($comment, 'post_id')->hiddenInput(['value' => $post->id]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($comment, 'comment')->textArea(['class' => 'form-control', 'rows' => 3]) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Comment', ['class' => 'btn btn-primary', 'name' => 'comment-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="panel panel-default">
    <?php foreach ($post->comments as $comment) : ?>
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6"><h5 class="">Author:
                        <?= $comment->user->name; ?>
                    </h5>
                </div>
                <div class="col-md-6"><h5 class="text-right">From:
                        <?= $comment->created_at; ?>
                    </h5></div>
            </div>
        </div>
        <div class="panel-body">
            <p>
                <?= $comment->comment; ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>
