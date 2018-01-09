<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;


use app\admin\model\RoleModel;
use app\admin\model\AdminModel;

class Admin extends Base
{
    //用户列表
    public function index()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['user_name'] = ['like', '%' . $param['searchText'] . '%'];
            }
            $user = new AdminModel();
            $selectResult = $user->getAdminsByWhere($where, $offset, $limit);

            $status = config('user_status');

            // 拼装参数
            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['last_login_time'] = date('Y-m-d H:i:s', $vo['last_login_time']);
                $selectResult[$key]['status'] = $status[$vo['status']];

                if( 1 == $vo['id'] ){
                    $selectResult[$key]['operate'] = '';
                    continue;
                }
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $user->getAllAdmins($where);  //总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加用户
    public function adminAdd()
    {
        if(request()->isPost()){

            $param = input('post.');

            $param['password'] = md5($param['password']);
            $user = new AdminModel();
            $flag = $user->insertAdmin($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $role = new RoleModel();
        $this->assign([
            'role' => $role->getRole(),
            'status' => config('user_status')
        ]);

        return $this->fetch();
    }

    // 编辑用户
    public function adminEdit()
    {
        $user = new AdminModel();

        if(request()->isPost()){

            $param = input('post.');

            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5($param['password']);
            }
            $flag = $user->editAdmin($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $role = new RoleModel();

        $this->assign([
            'user' => $user->getOneAdmin($id),
            'status' => config('user_status'),
            'role' => $role->getRole()
        ]);
        return $this->fetch();
    }

    // 删除用户
    public function adminDel()
    {
        $id = input('param.id');

        $role = new AdminModel();
        $flag = $role->delAdmin($id);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }

    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'admin/adminedit',
                'href' => url('admin/adminEdit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'admin/admindel',
                'href' => "javascript:adminDel(" .$id .")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
