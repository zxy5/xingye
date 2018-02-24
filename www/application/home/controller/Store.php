<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/2/24
 * Time: 14:26
 */

namespace app\home\controller;

use think\Controller;
use think\Db;

class Store extends Controller
{

    public function index(){
        $id = input('param.id');
        $this->assign('id',$id);
        return $this->fetch();
    }

    /**
     * 获取数据
     */
    public function get_data(){
        $id = input('param.id');
        //分页
        $limit = 10;
        $page = empty(input('param.page'))?1:input('param.page');
        $offset = ( $page - 1 ) * $limit;

        $validate = input('param.valid');
        if( empty($validate) || $validate==0 ){
            $is_va = 0;
        }else{
            $is_va = 1;
        }
        $where = [
            'coupons_id'   => $id,
            'is_use'       => 1,
            'is_validate'  => $is_va
        ];
        $list = Db::name('coupons_log')->where( $where )->order('add_time desc') ->limit($offset,$limit)->select();
        $count = Db::name('coupons_log')->where($where)->count();
        $pageCount = ceil($count/$limit);
        return json(array('data'=>$list,'page'=>$page,'pageCount'=>$pageCount,'count'=>$count));
    }

    /**
     * 验证
     */
    public function validate_code(){
        $id = input('param.id');
        $re = Db::name('coupons_log')->where('id',$id)->update(array('is_validate'=>1,'validate_time'=>time()));
        if( $re ){
            return msg(1,'','验证成功！');
        }else{
            return msg(-1,'','验证失败！');
        }
    }

}