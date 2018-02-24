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

        //分页
        $limit = 10;
        $page = empty(input('param.page'))?1:input('param.page');
        $offset = ( $page - 1 ) * $limit;

        $where = [
            'coupons_id'   => $id,
            'is_use'       => 1,
            'is_validate'  => 0
        ];

        $list = Db::name('coupons_log')->where( $where )->order('add_time desc') ->limit($offset,$limit)->select();
        $count = Db::name('coupons_log')->where($where)->count();
        $this->assign([
            'list'=>$list,
            'count'=>$count
        ]);
        return $this->fetch();
    }

    /**
     * 验证
     */
    public function validate_code(){
        $id = input('param.id');
        $re = Db::name('coupons_log')->where('id',$id)->update(array('is_validate'=>1));
        if( $re ){
            return msg(1,'','验证成功！');
        }else{
            return msg(-1,'','验证失败！');
        }
    }

}