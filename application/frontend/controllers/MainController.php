<?php

namespace frontend\controllers;

use frontend\models\Post;
use frontend\models\SignupForm;
use yii\web\Controller;

class MainController extends Controller
{
    public function actionIndex()
    {
        $posts = Post::gainLasts();
        return $this->render('index', ['posts' => $posts]);
    }

}
