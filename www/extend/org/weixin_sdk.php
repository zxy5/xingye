<?php
/**
*微信封转类sdk
**/
namespace org;
class Weixin_sdk
{
    public $appid = "";
    public $appsecret = "";
    public $lasttime='';
    public $access_token='';

    //构造函数，获取Access Token
    public function __construct($appid = NULL, $appsecret = NULL)
    {
        if($appid){
            $this->appid = $appid;
        }
        if($appsecret){
            $this->appsecret = $appsecret;
        }

        //hardcode
        $this->lasttime = $_SESSION['lasttime'];
        $this->access_token = $_SESSION['access_token'];

        $access_token = $this->getCache('access_token',7000);

        if (!$access_token){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->https_request($url);
            $result = json_decode($res, true);
            //save to Database or Memcache
            $access_token = $result["access_token"];
            $this->access_token = $access_token;
            $this->lasttime = time();




            $this->setCache('access_token',$access_token);

            $_SESSION['access_token']=$result['access_token'];
            $_SESSION['lasttime']=time();
        }
        else
        {
            $this->access_token = $access_token;
        }
    }

    //获取关注者列表
    public function get_user_list($next_openid = NULL)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->access_token."&next_openid=".$next_openid;
        $res = $this->https_request($url);
        return json_decode($res, true);
    }
    /**
     * 身份验证时获取code
     *
     * @param   string $appid
     * @param   string $callback 回调地址 
     */
    public function get_code($appid,$callback,$state=1){
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
        header("Location:".$url);
    }
    /**
     * 身份验证时获取openid
     * @param   string $appid
     * @param   string $appsecret
     * @param   string $code 
     * @return  string 字符串格式的返回结果
     */
    public function get_openid($appid,$appsecret,$code){
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';       
        $res=$this->https_request($get_token_url);
        $json_obj = json_decode($res,true);     
        //根据openid和access_token查询用户信息
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
        $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_info=$this->https_request($get_user_info_url);
        return json_decode($user_info,true);
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
                $data = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
                break;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->access_token;
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
    
    //上传临时多媒体文件
    public function upload_temp_media($type, $file)
    {
        $data = array("media"  => "@".$file);
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=".$type;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
     //上传永久多媒体文件
    /*$file_info=array(
    'filename'=>'/images/1.png',  //国片相对于网站根目录的路径
    'content-type'=>'image/png',  //文件类型
    'filelength'=>'11011'         //图文大小
    );*/
    public function upload_ever_media($type,$file_info)
    {
        
        //$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=".$type;
        $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$this->access_token."&type=".$type;
        $data = array("media"  => "@".BASE_ROOT_PATH.$file_info['filename'],"form-data"=>$file_info);
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

/**
 * 上传图文消息素材【订阅号与服务号认证后均可用】
 */

/**
 * @ 上传多媒体文件
 * @ access_token   是   调用接口凭证
 * @ type           是   媒体文件类型，分别有图片（image）、语音（voice）、视频（video），普通文件(file)
 * @ media          是   form-data中媒体文件标识，有filename、filelength、content-type等信息
 * *************************************************************************************************************
 * 图片（image）: 1M，支持JPG格式
 * 语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式
 * 视频（video）：10MB，支持MP4格式
 * 缩略图（thumb）：64KB，支持JPG格式
 * @ 成功上传后    type 媒体文件类型 ｜  media_id 媒体文件上传后获取的唯一标识 ｜ created_at 媒体文件上传时间戳
 * {"type":"TYPE","media_id":"MEDIA_ID","created_at":123456789}
 * 使用格式 upload_media('access_token','image',array("media"=>"@D:\WWW\OneThink\Uploads\image.jpg"))
 */
public function upload_media($type, $data) {
    $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->access_token.'&type=' . $type;
    $res=$this->https_request($url,$data);
    return json_decode($res,true);
}
    /**
     * 上传图文消息素材【订阅号与服务号认证后均可用】
     */
    public function media_uploadnews($data) {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=' . $this->access_token;
        $res=$this->https_request($url, $data);
        return json_decode($res, true);
    }
    //获取粉丝列表
    public function user_get() {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->access_token;
        $user_list=$this->https_request($url);
        return json_decode($user_list, true);
    }
    /**
     * 根据OpenID列表群发【订阅号不可用，服务号认证后可用】
     */
    public function message_mass_send($data) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $this->access_token;
        $res=$this->https_request($url, $data);
        return json_decode($res, true);
    }

    /* 获取错误信息 */

    public function get_error($code) {
        $error_msg = array(
            '-1' => '系统繁忙',
            '0' => '请求成功',
            '40001' => '获取access_token时AppSecret错误，或者access_token无效',
            '40002' => '不合法的凭证类型',
            '40003' => '不合法的OpenID',
            '40004' => '不合法的媒体文件类型',
            '40005' => '不合法的文件类型',
            '40006' => '不合法的文件大小',
            '40007' => '不合法的媒体文件id',
            '40008' => '不合法的消息类型',
            '40009' => '不合法的图片文件大小',
            '40010' => '不合法的语音文件大小',
            '40011' => '不合法的视频文件大小',
            '40012' => '不合法的缩略图文件大小',
            '40013' => '不合法的APPID',
            '40014' => '不合法的access_token',
            '40015' => '不合法的菜单类型',
            '40016' => '不合法的按钮个数',
            '40017' => '不合法的按钮个数',
            '40018' => '不合法的按钮名字长度',
            '40019' => '不合法的按钮KEY长度',
            '40020' => '不合法的按钮URL长度',
            '40021' => '不合法的菜单版本号',
            '40022' => '不合法的子菜单级数',
            '40023' => '不合法的子菜单按钮个数',
            '40024' => '不合法的子菜单按钮类型',
            '40025' => '不合法的子菜单按钮名字长度',
            '40026' => '不合法的子菜单按钮KEY长度',
            '40027' => '不合法的子菜单按钮URL长度',
            '40028' => '不合法的自定义菜单使用用户',
            '40029' => '不合法的oauth_code',
            '40030' => '不合法的refresh_token',
            '40031' => '不合法的openid列表',
            '40032' => '不合法的openid列表长度',
            '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
            '40035' => '不合法的参数',
            '40038' => '不合法的请求格式',
            '40039' => '不合法的URL长度',
            '40050' => '不合法的分组id',
            '40051' => '分组名字不合法',
            '41001' => '缺少access_token参数',
            '41002' => '缺少appid参数',
            '41003' => '缺少refresh_token参数',
            '41004' => '缺少secret参数',
            '41005' => '缺少多媒体文件数据',
            '41006' => '缺少media_id参数',
            '41007' => '缺少子菜单数据',
            '41008' => '缺少oauth code',
            '41009' => '缺少openid',
            '42001' => 'access_token超时',
            '42002' => 'refresh_token超时',
            '42003' => 'oauth_code超时',
            '43001' => '需要GET请求',
            '43002' => '需要POST请求',
            '43003' => '需要HTTPS请求',
            '43004' => '需要接收者关注',
            '43005' => '需要好友关系',
            '44001' => '多媒体文件为空',
            '44002' => 'POST的数据包为空',
            '44003' => '图文消息内容为空',
            '44004' => '文本消息内容为空',
            '45001' => '多媒体文件大小超过限制',
            '45002' => '消息内容超过限制',
            '45003' => '标题字段超过限制',
            '45004' => '描述字段超过限制',
            '45005' => '链接字段超过限制',
            '45006' => '图片链接字段超过限制',
            '45007' => '语音播放时间超过限制',
            '45008' => '图文消息超过限制',
            '45009' => '接口调用超过限制',
            '45010' => '创建菜单个数超过限制',
            '45015' => '回复时间超过限制',
            '45016' => '系统分组，不允许修改',
            '45017' => '分组名字过长',
            '45018' => '分组数量超过上限',
            '46001' => '不存在媒体数据',
            '46002' => '不存在的菜单版本',
            '46003' => '不存在的菜单数据',
            '46004' => '不存在的用户',
            '47001' => '解析JSON/XML内容错误',
            '48001' => 'api功能未授权',
            '50001' => '用户未授权该api',
        );
        return $error_msg[$code];
    }

    //https请求（支持GET和POST）
    protected function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    
    
    //下载对媒体文件
    public function download_media( $serverid ){
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$this->access_token."&media_id=".$serverid;
        $res = $this->https_request($url);
        return $res;
    }


    /**
     * 读缓存
     * @param $key
     * @param $val
     * @param $exceed
     */
    private  function getCache($key,$exceed)
    {
        //$this->setCache("abc","123");
        //echo($this->getCache("abc",5));

        $val = F($key);

        $arr = explode('|',$val);
        $time = $arr[0];
        $val  = $arr[1];
        if (time() > $exceed+$time)
        {
            return false;
        }

        return $val;

    }

    /**
     * 设置缓存
     * @param $key
     * @param $val
     */
    private  function setCache($key,$val)
    {
        //
        // 组合一个缓存值出来
        $val = time()."|".$val;

        F($key,$val);

    }
}