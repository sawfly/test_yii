<?php

namespace frontend\controllers;

use frontend\models\Comment;
use frontend\models\Post;
use frontend\models\User;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class PostsController extends Controller
{
    public function actionIndex()
    {
        $query = Post::findWithUser();
        $pagination = new Pagination(['defaultPageSize' => 5, 'totalCount' => $query->count()]);
        $posts = $query->orderBy('id')->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('index', [
            'posts' => $posts,
            'pagination' => $pagination,
        ]);
    }

    public function actionView($id)
    {
        $post = Post::findOne($id);
        $comment = new Comment();
        return $this->render('post', ['post' => $post, 'user' => new User(['scenario' => User::SCENARIO_SOFT_REG]),
            'comment' => $comment]);
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->identity->id) {
            return $this->goHome();
        }
        $post = new Post();
        if ($post->load(Yii::$app->request->post())) {
            if ($post->create()) {
                return $this->goBack(Url::toRoute("posts/$post->id"));
            }
        }
        return $this->render('newpost', ['post' => $post]);
    }

    public function actionChange($id)
    {
        $post = Post::findOne($id);
        if (!Yii::$app->user->identity->id || Yii::$app->user->identity->id != $post->user_id) {
            return $this->goHome();
        }
        if ($post->load(Yii::$app->request->post())) {
            if ($post->create()) {
                return $this->goBack(Url::toRoute("posts/$post->id"));
            }
        }
        return $this->render('change-post', ['post' => $post]);
    }

    public function actionDelete($id)
    {
        $post = Post::findOne($id);
        if (Yii::$app->user->identity->id && Yii::$app->user->identity->id == $post->user_id) {
            $post->delete();
        }
        return $this->goHome();
    }
}
