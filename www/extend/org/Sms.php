<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/13
 * Time: 13:32
 * 短信发送接口类
 */

namespace org;

class Sms
{
    /**
     * 短信发送
     */
    public function send($mobile , $content){
        $user_id = 'CDJS004262'; // 这里填写用户名
        $pass = 'zm0513@';// 这里填登陆密码
        $target = "http://sdk2.028lk.com:9880/sdk2/BatchSend2.aspx"; //url
        $content = mb_convert_encoding($content, "GBK", "auto"); //将内容编码为gbk
        //替换成自己的测试账号,参数顺序和wenservice对应
        $post_data = "CorpID=".$user_id."&Pwd=".$pass."&Mobile=".$mobile."&Content=".$content;//参数设置
        $gets = $this->ihuyi_Post($post_data,$target);//请求凌凯地址，并返回请求结果
        if ($gets >= 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 凌凯短信接口
     */
    function mysend_linkai($mobile, $content){
        //配置信息
//        $model_setting = Model('setting');
//        $mobile_username = $model_setting->getRowSetting('mobile_username');
//        $mobile_pwd = $model_setting->getRowSetting('mobile_pwd');
//        $user_id = $mobile_username['value']; // 这里填写用户名
//        $pass = $mobile_pwd['value'];// 这里填登陆密码
//        $target = "http://mb345.com:999/ws/batchSend.aspx"; //url
//        $content = mb_convert_encoding($content, "GBK", "auto"); //将内容编码为gbk
//        //替换成自己的测试账号,参数顺序和wenservice对应
//        $post_data = "CorpID=".$user_id."&Pwd=".$pass."&Mobile=".$mobile."&Content=".$content;//参数设置
//        $gets = $this->ihuyi_Post($post_data,$target);//请求凌凯地址，并返回请求结果
//        if ($gets >= 0){
//            return true;
//        }else{
//            return false;
//        }
    }

    /*
	您于{$send_time}绑定手机号，验证码是：{$verify_code}。【{$site_name}】
	0  提交成功
	30：密码错误
	40：账号不存在
	41：余额不足
	42：帐号过期
	43：IP地址限制
	50：内容含有敏感词
	51：手机号码不正确
	http://api.smsbao.com/sms?u=USERNAME&p=PASSWORD&m=PHONE&c=CONTENT
	*/
    private function mysend_smsbao($mobile,$content){

        $user_id = urlencode(C('mobile_username')); // 这里填写用户名

        //密码存在特殊字符，故去掉了urlencode()；
        $pass = C('mobile_pwd'); // 这里填登陆密码

        if(!$mobile || !$content || !$user_id || !$pass) return false;
        if(is_array($mobile)) $mobile = implode(",",$mobile);
        $mobile=urlencode($mobile);
        //$content=$content."【我的网站】";
        $content=urlencode($content);
//        return $pass;
        $pass =md5($pass);//MD5加密
        $url="http://api.smsbao.com/sms?u=".$user_id."&p=".$pass."&m=".$mobile."&c=".$content."";
        $res = file_get_contents($url);
//  	   return $res;
        $ok=$res=="0";
        if($ok)
        {
            return true;
        }
        return false;

    }

    function ihuyi_Post($curlPost,$url){
        $curl = curl_init(); //初始化
        curl_setopt($curl, CURLOPT_URL, $url); //访问地址
        curl_setopt($curl, CURLOPT_HEADER, false); //设置没有http头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//如果成功只将结果返回，不自动输出任何内容
        curl_setopt($curl, CURLOPT_NOBODY, true);//设置输出包中不包含body部分
        curl_setopt($curl, CURLOPT_POST, true); //POST请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost); //POST参数
        $return_str = curl_exec($curl); //执行设置好的curl会话，成功返回true,否则false
        curl_close($curl); //关闭
        return $return_str;
    }

}