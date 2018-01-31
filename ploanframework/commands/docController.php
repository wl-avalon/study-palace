<?php

namespace ploanframework\commands;

use yii\base\Controller;

class docController extends Controller{
    /**
     * This command echoes annotation of what you have entered as the file path.
     */
    public function actionAnnotation(){
        $args = $_SERVER["argv"];
        // Check args Num
        $count = $_SERVER['argc'];

        var_dump($args);
        var_dump($count);
    }
}