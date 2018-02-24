<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/2/24
 * Time: 14:26
 */

namespace app\home\controller;

use think\Controller;

class Store extends Controller
{

    public function index(){

        return $this->fetch();
    }

}