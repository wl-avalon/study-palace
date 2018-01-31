<?php

namespace ploanframework\controllers;

use rrxframework\base\JdbController;

class ProductController extends JdbController{
    public function actions(){
        return [
            'get' => 'ploanframework\actions\product\GetAction',
        ];
    }
}