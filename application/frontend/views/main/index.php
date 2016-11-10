<?php
/* @var $this yii\web\View */
$postPath = '/posts';
?>

<?php if (count($posts)) : ?>
    <h3> Last <?= count($posts); ?> posts</h3>
    <?php foreach ($posts as $post):?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6"><h3 class="panel-title"><a href="
                                <?= "$postPath/$post->id"; ?>
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
    <?php endforeach ?>
<?php  else : ?>
    <h3> There are no posts yet</h3>
<?php endif ?>

