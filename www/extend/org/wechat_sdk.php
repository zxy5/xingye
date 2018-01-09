<?php
/**
 *微信封转类sdk
 *新增的类
 **/
namespace org;
class Wechat_sdk
{
    public $appid = "";
    public $appsecret = "";
    public $ac_lasttime='';
    public $access_token='';
    public $jsapi_ticket='';
    public $ti_lasttime='';

    //构造函数，获取Access Token
    public function __construct($appid = NULL, $appsecret = NULL)
    {
        if($appid){
            $this->appid = $appid;
        }
        if($appsecret){
            $this->appsecret = $appsecret;
        }

        $this->ac_lasttime = $_SESSION['ac_lasttime'];
        $this->access_token = $_SESSION['access_token'];

        if (empty($_SESSION['access_token']) || empty($_SESSION['ac_lasttime'])){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->https_request($url);
            $result = json_decode($res, true);
            //save to Database or Memcache
            $this->access_token = $result["access_token"];
            $this->ac_lasttime = time();
            $_SESSION['access_token']=$result['access_token'];
            $_SESSION['ac_lasttime']=time();
        }
//         if (time() > ($this->ac_lasttime + 7200)){
//         }
    }

    /**
     * @return array
     * 返回签名包
     */

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $key = $_COOKIE['key'];
//         $key = '8472b7957273cc1e70287109ac947f4b';
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=".$_SESSION['jsapi_ticket']."&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }




    /**
     * 创建随机字符串
     * @param int $length  需要返回的字符串长度
     * @return string      返回截取的字符串
     */
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 生成JsApiTicket
     */
    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $this->ti_lasttime = $_SESSION['ti_lasttime'];
        $this->jsapi_ticket = $_SESSION['jsapi_ticket'];

        if (empty($_SESSION['ti_lasttime']) || empty($_SESSION['jsapi_ticket'])){
            $accessToken = $_SESSION['access_token'];
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";
            $res = json_decode($this->https_request($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $_SESSION['ti_lasttime'] = time();
                $_SESSION['jsapi_ticket'] = $ticket;
            }
        }
//         if (time() > ($this->ti_lasttime + 7200)) {
//         }
    }



    //获取关注者列表
    public function get_user_list($next_openid = NULL)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->access_token."&next_openid=".$next_openid;
        $res = $this->https_request($url);
        return json_decode($res, true);
    }

    //获取用户基本信息
    public function get_user_info($openid)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$openid."&lang=zh_CN";
        $res = $this->https_request($url);
        return json_decode($res, true);
    }

    //创建菜单
    public function create_menu($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

    //发送客服消息，已实现发送文本，其他类型可扩展
    public function send_custom_message($touser, $type, $data)
    {
        $msg = array('touser' =>$touser);
        switch($type)
        {
            case 'text':
                $msg['msgtype'] = 'text';
                $msg['text']    = array('content'=> urlencode($data));
                break;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->access_token;
        return $this->https_request($url, urldecode(json_encode($msg)));
    }

    //生成参数二维码
    public function create_qrcode($scene_type, $scene_id)
    {
        switch($scene_type)
        {
            case 'QR_LIMIT_SCENE': //永久
                $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
                break;
            case 'QR_SCENE':       //临时
                $data = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
                break;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->access_token;
        //echo $data;exit;
        $res = $this->https_request($url, $data);
        $result = json_decode($res, true);
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($result["ticket"]);
    }

    //创建分组
    public function create_group($name)
    {
        $data = '{"group": {"name": "'.$name.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

    //移动用户分组
    public function update_group($openid, $to_groupid)
    {
        $data = '{"openid":"'.$openid.'","to_groupid":'.$to_groupid.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

    //上传多媒体文件
    public function upload_media($type, $file)
    {
        $data = array("media"  => "@".dirname(__FILE__).'\\'.$file);
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=".$type;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
    
    //下载对媒体文件
    public function download_media( $serverid ){
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$this->access_token."&media_id=".$serverid;
        $res = $this->https_request( $url );
        return $res;
    }

    //https请求（支持GET和POST）
    protected function https_request($url, $data = null)
    {
        $curl = curl_init();
        //需要获取的URL地址，也可以在curl_init()函数中设置
        curl_setopt($curl, CURLOPT_URL, $url);
        //禁用后cURL将终止从服务端进行验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        //检查服务器SSL证书中是否存在一个公用名(common name)。
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)){
            //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
            curl_setopt($curl, CURLOPT_POST, 1);
            //全部数据使用HTTP协议中的"POST"操作来发送;$data可以是使用一个以字段名为键值，字段数据为值的数组
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行给定的cURL会话。 成功时返回 TRUE， 或者在失败时返回 FALSE。
        //然而，如果 CURLOPT_RETURNTRANSFER选项被设置，函数执行成功时会返回执行的结果，失败时返回 FALSE 。
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    

}
?>