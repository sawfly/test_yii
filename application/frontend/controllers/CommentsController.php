<?php

namespace frontend\controllers;

use frontend\models\Comment;
use frontend\models\Post;
use frontend\models\User;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class CommentsController extends Controller
{
    public function actionCreate()
    {
        $comment = new Comment();
        if ($comment->load(Yii::$app->request->post())) {
            if ($comment->user_id && $comment->create()) {
                return $this->goBack(Url::toRoute("posts/$comment->post_id"));
            } elseif (!$comment->user_id) {
                $user = new User(['scenario'=>User::SCENARIO_SOFT_REG]);
                $user->load(Yii::$app->request->post());
                if($user->save() && $comment->setUserId($user->id)->create()){
                    return $this->goBack(Url::toRoute("posts/$comment->post_id"));
                }
            }
        }
    }

}
