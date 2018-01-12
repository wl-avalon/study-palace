<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午7:29
 */

namespace app\commands;
use app\library\Request;
use yii\console\Controller;

class MainController extends Controller
{
    public function actionIndex(){
        echo Request::curl('http://www.91taoke.com/Juanzi/ajaxlist?id=3%2C9%2C27%2C1000001&zjid=0&tixing=0&nandu=0&search=&leixing=0&p=2&xuekename=%E8%AF%AD%E6%96%87');
        echo Request::curl('http://www.91taoke.com/Juanzi/ajaxlist?id=5%2C101%2C119%2C1118293&zjid=0&tixing=0&nandu=0&leixing=0&xuekename=%E8%AF%AD%E6%96%87');
    }
}