<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/20
 * Time: 10:40
 *抽奖api链接生成类
 */

namespace app\admin\controller;
use think\Controller;

class ApiAwardEvent extends Controller
{
    private $appkey = '30798ef7c6c3d5584330e387059305c7';
    private $md5key = 'b3163c19e10fd5af8a19';
    private $encryptKey = '0563b136a52cdf4';
    //private $aeskey = '90563b136a52cdf4';
    private $url = 'http://api.hdjie.net/api/v1/gateway/';
    /**
     * @param $api string 请求接口
     * @param string $data 参数
     * @return mixed
     */
    private function connect($api,$p = ''){
        $time = time();
        $p = $p == '' ? '' : '&p='.$p;
        $url = $this->url.$this->appkey.'/'.$api;
        $url .= '?time='.$time.'&sign='.md5('appkey='.$this->appkey.$p.'&time='.$time.'&key='.$this->md5key);
        $url .= $p;
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * @param $str
     * @return string
     * 对id进行加密
     */
    private function aes($str){
        //$str = iconv('utf-8', 'gb2312', $str);
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $pad = $block - (strlen($str) % $block);
        $str =  $str . str_repeat(chr($pad), $pad);

        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_DEV_URANDOM);
        $str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,'90563b136a52cdf4',$str, MCRYPT_MODE_ECB,$iv);
        return bin2hex($str);
    }
    public function s(){
        $a = $this->aes('couponCategoryId=1');
        var_dump($a);
    }
    /**
     * 	获取优惠券分类
     */
    public function getCategories(){
        $data = $this->connect('console.coupon.getCategories');
        return json_decode($data,true)['data'];
    }

    /**
     * 	根据类型ID获取优惠券列表信息
     */
    public function getCouponList($id){
        $p = $this->aes('index=1&size=100&couponCategoryId='.$id);
        $data = $this->connect('console.coupon.getCouponList',$p);
        return json_decode($data,true)['data'];
    }

    /**
     * 	获取具体优惠券的兑换信息
     */
    public function getCouponCode($id){
        $p = $this->aes('couponId='.$id);
        $data = $this->connect('console.coupon.getCouponCode',$p);
        return json_decode($data,true)['data'];
    }

}