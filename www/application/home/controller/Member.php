<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/16
 * Time: 13:55
 */

namespace app\home\controller;


use think\Db;

class Member extends Base
{
    /**
     * 个人中心
     */
    public function index(){
        $member_id = session('member_id');
        $member_info = Db::name('member')->where('id',$member_id)->find();
        $this->assign( 'member_info', $member_info );
        return $this->fetch();
    }

    /**
     * 领取记录
     */
    public function get_coupons_log(){
        if( request()->isAjax() ){

            $limit = 10;
            $offset = (input('param.page') - 1) * $limit;

            //领取记录
            $where = array();
            $where['a.member_id'] = session('member_id');
            $where['a.is_validate'] = 0;
            $coup_list = Db::name('coupons_log')
                ->alias('a')->field('a.*,b.name,b.id as c_id,b.thumd,b.discount,c.class_thumd')
                ->join('__COUPONS__ b','a.coupons_id=b.id','LEFT')
                ->join('__COUPONS_CLASS__ c','b.class_id=c.class_id','LEFT')
                ->where($where)
                ->limit($offset,$limit)->order('a.id desc')->select();

            //整理数据
            foreach( $coup_list as $k=>$v ){
                if( empty($v['thumd'])||$v['thumd']=='' ){
                    $coup_list[$k]['thumd'] = $v['class_thumd'];
                }
            }
            return json(array('data'=>$coup_list));
        }else{
            return json(msg(-1,'','非法请求！'));
        }
    }

    /**
     * 获取中奖记录
     */
    public function get_award_log(){
        if( request()->isAjax() ){
            $limit = 10;
            $offset = (input('param.page') - 1) * $limit;

            //中奖记录
            $condition = array();
            $condition['a.member_id'] = session('member_id');
            $condition['a.is_use'] = 0;
            $award_log = Db::name('award_log')
                ->alias('a')->field('a.*,b.name,b.id as c_id,b.thumd,b.discount,b.type')
                ->join('__AWARD__ b','a.award_id=b.id','LEFT')
                ->where($condition)
                ->limit($offset,$limit)->order('a.id desc')->select();
            return json(array('data'=>$award_log));

        }else{
            return json(msg(-1,'','非法请求！'));
        }
    }

    public function get_ccode_str(){
        $id = input('param.id');
        $info = Db::name('coupons_log')->where('id',$id)->find();
        if( empty($info) ){
            return json(msg('-1','','优惠券不存在！'));
        }
        if( !empty($info['code_str']) ){
            return json(msg(1,$info['code_str'],'使用成功！'));
        }
        $data = [
            'is_use' => 1,
            'code_str' =>''
        ];
        $re = Db::name('coupons_log')->where('id',$id)->update( $data );
        return '';
    }

    /**
     * 优惠券详情
     */
    public function coupons_detail(){
        $id = input('param.id');
        $info = Db::name('coupons_log')->alias('a')
                    ->field('a.*,b.name,b.id as c_id,b.thumd,b.store_phone,b.store_address,b.desc,b.discount,b.start_time,b.end_time,c.class_thumd')
                    ->join('__COUPONS__ b','a.coupons_id=b.id','LEFT')
                    ->join('__COUPONS_CLASS__ c','b.class_id=c.class_id','LEFT')
                    ->where('a.id',$id)->find();
        //是否有上传缩略图片，默认分类图片
        if( empty( $info['thumd'] ) ){
            $info['thumd'] = $info['class_thumd'];
        }
        $this->assign('info',$info);

        return $this->fetch();
    }

    /**
     * 奖品详情
     */
    public function award_detail(){
        $id = input('param.id');
        $info = Db::name('award_log')->alias('a')
            ->field('a.*,b.name,b.id as c_id,b.thumd,b.store_phone,b.store_address,b.desc,b.discount')
            ->join('__AWARD__ b','a.award_id=b.id','LEFT')
            ->where('a.id',$id)->find();

        $this->assign('info',$info);
        return $this->fetch();
    }
}