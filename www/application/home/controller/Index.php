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
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 线下门店优惠券
     */
    public function coupons(){
        $list = Db::name('store_coupons')->field('*')->paginate();

    }

    /**
     * 抽奖
     */
    public function award(){

        return $this->fetch();
    }

    /**
     * 计算中奖——奖品
     */
    public function lottery(){

    }

}
