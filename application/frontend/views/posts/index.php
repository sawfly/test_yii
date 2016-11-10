<?php

use yii\widgets\LinkPager;

$postsPath = 'posts';
?>
<h3>Posts</h3>
<?php foreach ($posts as $post): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6"><h3 class="panel-title"><a href="
                                <?= "$postsPath/$post->id"; ?>
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
                <?= substr($post->post, 0, 128); ?>
            </p>
        </div>
        <div class="panel-footer">Author:
            <?= $post->user->name; ?>
        </div>
    </div>
<?php endforeach; ?>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<?php if (!Yii::$app->user->isGuest) : ?>
    <a href="/posts/create"><button type="submit" class="btn btn-success">Create Post</button></a>
<?php endif; ?>
