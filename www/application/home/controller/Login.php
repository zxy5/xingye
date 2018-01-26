<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/20
 * Time: 11:16
 *
 * 登录、注册类
 */

namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Validate;

class Login extends Controller
{

    public function index(){
        $this->redirect('/home/login/login');
    }

    /**
     * 登录
     * @return mixed
     */
    public function login(){
        return $this->fetch();
    }

    /**
     * 登录操作
     */
    public function dologin(){
        $phone = input('param.phone');
        $code = input('param.code');

        $validate = new Validate();
        $validate->rule('phone','require|/^1[345789]\d{9}$/');
        $validate->message('phone','手机格式不正确');
        if( !$validate->check( array( 'phone'=>$phone ) ) ){
            return json( msg( '-1','',$validate->getError() ) );
        }
        if( $code != session('send_code') ){
            return json( msg( '-1','', '验证码错误！' ) );
        }
        if( time()-session('send_code_time') > 300 ){
            return json( msg( '-1','', '验证码超时！' ) );
        }
        $member = Db::name('member')->where('member_phone',$phone)->find();
        if( $member ){
            session('member_id',$member['id']);
            return json( msg( 1 , '' , '登录成功！' ) );
        }else{
            $re = Db::name('member')->insertGetId(array('member_name'=>$phone,'member_phone'=>$phone));
            if( $re ){
                session( 'member_id',$re );
                session( 'member_phone',$phone );
                return json( msg( 1 , '' , '登录成功！' ) );
            }else{
                return json( msg( -1 , '' , '登录失败！' ) );
            }
        }
    }

    /**
     * 获取验证码
     */
    public function get_code(){
        $code = rand(100000,999999);
        //判断是否为连续发送
        if( !empty(session('send_code_time'))&&time()-session('send_code_time')<60 ){
            return json( msg( -1 , '' , '验证码已发送！' ) );
        }
        //手机号验证
        $validate = new Validate();
        $validate->rule('phone','require|/^1[345789]\d{9}$/');
        $validate->message('phone','手机格式不正确');
        if( !$validate->check( array( 'phone'=>input('param.phone') ) ) ){
            return json( msg( '-1','',$validate->getError() ) );
        }

        session('send_code',$code);
        session('send_code_time',time());
        return json( msg( 1 , $code , '发送成功！' ) );
    }

}