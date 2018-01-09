<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/12
 * Time: 14:16
 */

namespace app\admin\controller;


use app\admin\model\MemberModel;
use think\Db;

class Member extends Base
{
    /**
     * 会员列表
     */
    public function index(){
        $where = array();
        if( !empty($_GET['member_name']) ){
            $where['member_name'] = ['like','%'.$_GET['member_name'].'%'];
        }
        $memberModel = new MemberModel();
        $list = $memberModel->getMemberList($where);
        $this->assign('list',$list);
        $this->assign('get',$_GET);
        return $this->fetch();
    }
    // 删除用户
    public function member_del()
    {
        $id = input('param.id');
        $member = new MemberModel();
        $re = $member->delMemberById($id);
        return json($re);
    }

}