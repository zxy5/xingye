<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/20
 * Time: 17:14
 */

namespace app\home\controller;


use think\Controller;

class Base extends Controller
{

    public function _initialize()
    {
        session('member_id','1');
        session('member_phone','15858282359');
        //检测用户是否登录
//        if( empty( session('member_id') ) ){
//            $this->redirect('login/index');
//        }
    }

}