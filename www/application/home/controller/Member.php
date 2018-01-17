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
//        $member_id = session('member_id');
        $member_id = 1;
        $member_info = Db::name('member')->where('id',$member_id)->find();

        //领取记录
        $where = array();
        $where['a.member_id'] = $member_id;
        $where['a.is_validate'] = 0;
        $coup_list = Db::name('coupons_log')
                        ->alias('a')->field('a.*,b.name,b.id as c_id,b.thumd,b.discount')
                        ->join('__COUPONS__ b','a.coupons_id=b.id','LEFT')->where($where)->paginate(20);

        //中奖记录
        $condition = array();
        $condition['a.member_id'] = $member_id;
        $condition['a.is_use'] = 0;
        $award_log = Db::name('award_log')
                        ->alias('a')->field('a.*,b.name,b.id as a_id,b.thumd,b.discount')
                        ->join('__AWARD__ b','a.award_id=b.id','LEFT')->where($condition)->paginate(20);


        $this->assign([
            'award_log' => $award_log,
            'coup_list' => $coup_list,
            'member_info'=> $member_info
        ]);
        return $this->fetch();
    }
}