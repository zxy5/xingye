<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/13
 * Time: 14:29
 *
 *
 * 抽奖管理类
 */
namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\model\AwardModel;
use think\Db;
use PHPExcel;
use PHPExcel_IOFactory;

class Award extends Base
{
    /**
     * 奖品列表
     */
    public function award_list(){
        $awarModel = new AwardModel();
        $list = $awarModel->getAwardList();
        $this->assign('list',$list);
        $this->assign('type',array('1'=>'虚拟','2'=>'实物'));
        return $this->fetch();
    }

    /**
     * 重置密码
     * 密码为登录手机号码
     */
    public function reset_pass(){
        $id = input('param.id');
        $model = new AwardModel();
        $login_phone = $model->getAwardById($id,'login_phone');
        $re = $model->resetPasswordById($id,md5($login_phone['login_phone']));
        return json($re);
    }

    /**
     * 编辑奖品
     */
    public function award_edit(){
        $awModel = new AwardModel();
        if( request()->isAjax() ){
            $where['id']  = ['neq',input('param.id')];
            $sum = $awModel->getChanceSum($where);
            if( $sum + intval( input('param.chance') )>10000){
                return json(msg(-1, '', '概率和应小于10000'));
            }
            $data = [
                'name' => input('param.name'),
                'desc' => input('param.desc'),
                'thumd' => input('param.thumd'),
                'type' => input('param.type'),
                'discount' => input('param.discount'),
                'store_address' => input('param.store_address'),
                'store_phone' => input('param.store_phone'),
//                'login_phone' => input('param.login_phone'),
                'num' => input('param.num'),
                'chance' => input('param.chance'),
//                'api_id' => input('param.api_id'),
                'update_time' => time(),
                'user_id' => session('id')
            ];
            $re = $awModel->editAwardById(input('param.id'),$data);
            return json($re);
        }else{
            $id = input('param.id');
            $info = $awModel->getAwardById( $id );
            $this->assign('type',array('1'=>'虚拟','2'=>'实物'));
            $this->assign('info',$info);
            return $this->fetch();
        }
    }

    /**
     * 添加奖品
     */
    public function award_add(){
        if(request()->isAjax()){
            $awModel = new AwardModel();
            $sum = $awModel->getChanceSum();
            if( $sum + intval( input('param.chance') )>10000){
                return json(msg(-1, '', '概率和应小于10000'));
            }
            $data = [
                'name' => input('param.name'),
                'desc' => input('param.desc'),
                'thumd' => input('param.thumd'),
                'discount' => input('param.discount'),
                'store_address' => input('param.store_address'),
                'store_phone' => input('param.store_phone'),
//                'login_phone' => input('param.login_phone'),
//                'login_password' => md5(input('param.login_phone')),
                'type' => input('param.type'),
                'num' => input('param.num'),
                'chance' => input('param.chance'),
//                'api_id' => input('param.api_id'),
                'add_time' => time(),
                'update_time' => time(),
                'user_id' => session('id')
            ];
            $re = $awModel->insertAward($data);
            return json($re);
        }else{
            $this->assign('type',array('1'=>'虚拟','2'=>'实物'));
            return $this->fetch();
        }
    }

    /**
     * 中奖记录
     */
    public function award_log(){
        $ex_url = '/admin/award/award_explod';
        $where = array();
        if( !empty($_GET['type']) ){
            $where['b.type'] =  trim($_GET['type']);
            $ex_url .= '/type/'.trim($_GET['type']);
        }
        if( !empty( $_GET['start'] ) ){
            $where['a.add_time'] =  ['>=',strtotime($_GET['start'])];
            $ex_url .= '/start/'.trim($_GET['start']);
        }
        if( !empty( $_GET['end'] ) ){
            $where['a.add_time'] =  ['<=',strtotime($_GET['end'])];
            $ex_url .= '/end/'.trim($_GET['end']);
        }
        if( !empty($_GET['is_use']) ){
            $where['a.is_use'] =  trim($_GET['is_use'])-1;
            $ex_url .= '/is_use/'.trim($_GET['type']);
        }
        $list = Db::name('award_log')->alias('a')
                ->field('a.*,b.name,b.type,c.member_phone')
                ->join( '__AWARD__ b' , 'a.award_id=b.id' , 'LEFT' )
                ->join( '__MEMBER__ c' , 'a.member_id=c.id' , 'LEFT' )
                ->where( $where )->order('a.add_time desc')->paginate();

        $this->assign('type',array('1'=>'虚拟','2'=>'实物'));
        $this->assign('list',$list);
        $this->assign('get',$_GET);
        $this->assign('ex_url',$ex_url);
        return $this->fetch();
    }

    /**
     * 更改实物奖品兑换状态
     */
    public function award_log_edit(){
        $id = input('param.id');
        $re = Db::name('award_log')->where('id',$id)->update(array('is_use'=>1));
        if( $re ){
            return json(msg( 1 , '' , '更改成功！' ));
        }else{
            return json(msg( -1 , '' , '更改失败！' ));
        }
    }

    /**
     * 导出中奖记录
     */
    public function award_explod(){
        $where = array();
        if( !empty(input('param.type')) ){
            $where['a.type'] = input('param.type');
        }
        if( !empty($_GET['start']) ){
            $where['a.add_time'] = ['>=',strtotime($_GET['start'])];
        }
        if( !empty($_GET['end']) ){
            $where['a.add_time'] = ['<=',strtotime($_GET['end'])];
        }
        if( !empty($_GET['is_use']) ){
            $where['a.is_use'] =  trim($_GET['is_use'])-1;
        }
        $data = Db::name('award_log')->alias('a')
            ->field('a.*,b.name,b.type,c.member_phone')
            ->join( '__AWARD__ b' , 'a.award_id=b.id' , 'LEFT' )
            ->join( '__MEMBER__ c' , 'a.member_id=c.id' , 'LEFT' )
            ->where( $where )->order('a.add_time desc')->select();

        $type = array('1'=>'虚拟','2'=>'实物');
        $use = array('未兑换','已兑换');

        $PHPExcel = new PHPExcel();
        $PHPSheet = $PHPExcel->getActiveSheet(); //获得当前活动sheet的操作对象
        $PHPSheet->setTitle('excel数据导出'); //给当前活动sheet设置名称
        //给当前活动sheet填充数据，数据填充是按顺序一行一行填充的
        $PHPSheet->setCellValue('A1','ID')
            ->setCellValue('B1','中奖时间')
            ->setCellValue('C1','中奖者')
            ->setCellValue('D1','奖品')
            ->setCellValue('E1','奖品类型')
            ->setCellValue('F1','兑换状态')
            ->setCellValue('G1','中奖者填写电话')
            ->setCellValue('H1','中奖者填写地址');
        foreach( $data as $k => $v ){
            $key = $k+2;
            $PHPSheet->setCellValue('A'.$key , $v['id'])
                ->setCellValue('B'.$key , date('Y-m-d',$v['add_time']))
                ->setCellValue('C'.$key , $v['member_phone'])
                ->setCellValue('D'.$key , $v['name'])
                ->setCellValue('E'.$key , $type[$v['type']])
                ->setCellValue('F'.$key , $use[$v['is_use']])
                ->setCellValue('G'.$key , $v['mobile'])
                ->setCellValue('H'.$key , $v['address']);
        }
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="中奖记录表.xls"');
        header('Cache-Control: max-age=0');
        $PHPWriter->save ( 'php://output' );
        exit;
    }

    /**
     * 上传缩略图片
     */
    public function uploadImg(){
        if(request()->isAjax()){
            $file = request()->file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload' . DS . '/award');
            if($info){
                $src =  '/upload/award' . '/' . date('Ymd') . '/' . $info->getFilename();
                return json(msg(0, ['src' => $src], ''));
            }else{
                // 上传失败获取错误信息
                return json(msg(-1, '', $file->getError()));
            }
        }
    }

    /**
     * 删除奖品
     */
    public function award_del(){
        $id = input('param.id');
        $awardModel = new AwardModel();
        $re = $awardModel->delAwardById($id);
        return json($re);
    }


}