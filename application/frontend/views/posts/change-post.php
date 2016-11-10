<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(['id' => 'post-form', 'method'=>'PUT']); ?>
        <div class="form-group">
            <?= $form->field($post, 'title')->textInput(['autofocus' => true]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($post, 'post')->textArea(['class' => 'form-control', 'rows' => 21]) ?>
        </div>
        <div class="form-group hidden">
            <?= $form->field($post, 'user_id')->hiddenInput(['value'=>Yii::$app->user->identity->id]) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' => 'post-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

