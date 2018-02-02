<?php
namespace app\home\controller;

use think\Controller;
use think\Db;

class Index extends Base
{
    /**
     * 首页
     * @return mixed
     */
    public function index(){
        //获取分类
        $type_list = Db::name('coupons_class')->order('class_sort desc,class_id desc')->select();
        //获取推荐券
        $list = Db::name('coupons')->alias('a')->field('a.id,a.name,a.discount,a.thumd,a.store_address,a.store_phone,b.class_thumd')
                    ->join('__COUPONS_CLASS__ b','a.class_id=b.class_id','LEFT')
                    ->where(array('a.is_recommend'=>1,'a.status'=>1))->order('a.id desc')->select();

        $this->assign([
            'list'=>$list,
            'type_list'=>$type_list
        ]);
        return $this->fetch();
    }

    /**
     * 券列表分类
     */
    public function  coupons_list(){
        if( request()->isAjax() ){

            $limit = 10;
            $offset = (input('param.page') - 1) * $limit;

            //优惠券列表
            $list = Db::name('coupons')->field('id,name,discount,thumd,store_address,store_phone')
                ->where('class_id',input('param.class_id'))->limit($offset,$limit)->order('id desc')->select();
            $class_info = Db::name('coupons_class')->field('class_id,class_thumd')->where('class_id',input('param.class_id'))->find();
            //整理数据
            foreach( $list as $k=>$v ){
                   if( empty($v['thumd'])||$v['thumd']=='' ){
                        $list[$k]['thumd'] = $class_info['class_thumd'];
                   }
            }
            return json(array('data'=>$list));

        }else{
            //分类id
            $class_id = input('param.id');
            $class_info = Db::name('coupons_class')->field('class_id,class_name')->where('class_id',$class_id)->find();
            $this->assign('class_info',$class_info);
            return $this->fetch();
        }
    }

    /**
     * 领取优惠券
     */
    public function add_coupons_log(){
        $id = input('param.id');
        if( !is_numeric( $id ) ){
            return json(msg(-1,'','数据错误！'));
        }
        //查看领取券中是否有未使用的该种券
        $where = [
            'member_id' => session('member_id'),
            'coupons_id' => $id,
            'is_validate' => 0
        ];
        $info = Db::name('coupons_log')->field('id')->where( $where )->find();
        if( !empty($info) ){
            return json(msg(-1,'','该券已领取！'));
        }

        $data = [
            'member_id' => session('member_id'),
            'member_phone' => session('member_phone'),
            'add_time' => time(),
            'coupons_id' => $id,
        ];
        $insert = Db::name('coupons_log')->insert($data);
        if( $insert ){
            return json(msg(1,'','领取成功！'));
        }else{
            return json(msg(-1,'','领取失败！'));
        }
    }

    /**
     * 抽奖
     */
    public function award(){
        $list = Db::name('award')->field('id,name,chance,num,thumd,discount')->order('sort desc')->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 计算中奖——奖品
     */
    public function lottery(){
        //判断是否能够抽奖
        $record = Db::name('award_record')->where('member_id',session('member_id'))->order('add_time desc')->find();
        if( !empty($record) ){
            $week = date('W',$record['add_time']);
            $now = date('W',time());
            if( $now==$week ){
                return json(msg('0','','你本周已抽过奖啦，下周再来吧！'));
            }
        }
        //记录抽奖时间
        Db::name('award_record')->insert(['add_time'=>time(),'member_id'=>session('member_id')]);

        //产生随机数
        $rand_num = rand(1,10000);
        $list = Db::name('award')->field('id,name,chance,num,thumd')->select();
        $sum = 0;
        $jp_key = -1;
        foreach( $list as $k=>$v ){
            if( $v['chance']!=0 ){
                $max = $sum + $v['chance'];
                if( $rand_num > $sum && $rand_num <= $max ){
                    $jp_key = $k;
                    break;
                }
                $sum += $v['chance'];
            }
        }
        if( $jp_key == -1 ){
            //奖品总数
            $count = Db::name('award')->count();
            return json(msg('-1',$count,'运气欠佳！'));
        }
        $award = $list[$jp_key];

        if( $award['num']<1 ){
            return json(msg('-2','','奖品已领完！'));
        }
        $where = [
            'member_id'=>session('member_id'),
            'award_id'=>$award['id'],
            'is_validate'=>0
        ];
        $has = Db::name('award_log')->where($where)->find();
        if( !empty($has) ){
            return json(msg('-3','','你已中过该奖！'));
        }
        $data = [
            'member_id'=>session('member_id'),
            'member_phone'=>session('member_phone'),
            'award_id'=>$award['id'],
            'add_time'=>time()
        ];
        Db::startTrans();
        try{
            $in = Db::name('award_log')->insertGetId($data);
            $re = Db::name('award')->where('id',$award['id'])->setDec('num',1);
            Db::commit();
        }catch (\Exception $e) {
           Db::rollback();
        }
        if( empty($in) || empty($re) ){
            return json(msg('-4','','系统错误！'));
        }
        return json(msg('1',$award,'恭喜你中奖了！'));
    }

}











